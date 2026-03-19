<?php

namespace App\Jobs;

use App\Models\ChallengeSubmission;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessSubmissionAI implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public readonly ChallengeSubmission $submission
    ) {}

    public function handle(): void
    {
        $submission = $this->submission->fresh();
        if (! $submission) return;

        $apiKey = config('services.openai.key');

        // ── Fallback: no API key ──────────────────────────────────
        if (! $apiKey) {
            Log::warning('ProcessSubmissionAI: No OpenAI API key configured. Pushing to manual_review.');
            $submission->update(['status' => 'manual_review']);
            return;
        }

        // ── Read image as base64 ─────────────────────────────────
        try {
            $imagePath = $submission->photo_path;
            $imageData = Storage::disk('public')->get($imagePath);
            $base64    = base64_encode($imageData);
            $mimeType  = Storage::disk('public')->mimeType($imagePath) ?? 'image/jpeg';
        } catch (\Throwable $e) {
            Log::error('ProcessSubmissionAI: Could not read photo', ['error' => $e->getMessage()]);
            $submission->update(['status' => 'manual_review']);
            return;
        }

        // ── Build prompt ─────────────────────────────────────────
        $challenge = $submission->challenge;
        $keywords  = implode(', ', $challenge->ai_keywords ?? []);
        $prompt    = "Does this photo show evidence of '{$challenge->title}'? "
                   . "The photo should contain: {$keywords}. "
                   . "Reply ONLY with valid JSON (no markdown): "
                   . '{"valid": true/false, "confidence": 0-100, "detected_labels": ["label1", "label2"]}';

        // ── Call OpenAI Vision API ───────────────────────────────
        try {
            $response = Http::withToken($apiKey)
                ->timeout(45)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'      => 'gpt-4o',
                    'max_tokens' => 200,
                    'messages'   => [[
                        'role'    => 'user',
                        'content' => [
                            ['type' => 'text', 'text' => $prompt],
                            ['type' => 'image_url', 'image_url' => [
                                'url'    => "data:{$mimeType};base64,{$base64}",
                                'detail' => 'low',
                            ]],
                        ],
                    ]],
                ]);

            if ($response->failed()) {
                throw new \RuntimeException('OpenAI API error: ' . $response->body());
            }

            $content = $response->json('choices.0.message.content', '');
            // Strip possible markdown code fences
            $content = preg_replace('/```(?:json)?\s*([\s\S]*?)```/', '$1', trim($content));
            $result  = json_decode($content, true);

            $confidence   = (int) ($result['confidence'] ?? 0);
            $detectedLabels = $result['detected_labels'] ?? [];

        } catch (\Throwable $e) {
            Log::error('ProcessSubmissionAI: API call failed', ['error' => $e->getMessage()]);
            // Fallback to manual_review so admin can decide
            $submission->update(['status' => 'manual_review']);
            return;
        }

        // ── Evaluate result ──────────────────────────────────────
        if ($confidence >= 70) {
            $submission->update([
                'status'    => 'manual_review',
                'ai_score'  => $confidence,
                'ai_labels' => $detectedLabels,
            ]);
        } else {
            $submission->update([
                'status'    => 'rejected',
                'ai_score'  => $confidence,
                'ai_labels' => $detectedLabels,
            ]);
        }

        Log::info('ProcessSubmissionAI: done', [
            'submission_id' => $submission->id,
            'confidence'    => $confidence,
            'status'        => $submission->fresh()->status,
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessSubmissionAI: job failed permanently', [
            'submission_id' => $this->submission->id,
            'error'         => $exception->getMessage(),
        ]);
        // Push to manual_review so admin can decide
        $this->submission->update(['status' => 'manual_review']);
    }
}
