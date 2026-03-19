<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessSubmissionAI;
use App\Models\Challenge;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChallengeSubmissionController extends Controller
{
    /**
     * Layer 1: Show camera capture form for a challenge.
     */
    public function create(Challenge $challenge)
    {
        // Check user hasn't already submitted today
        $existingSubmission = ChallengeSubmission::where('user_id', auth()->id())
            ->where('challenge_id', $challenge->id)
            ->whereDate('created_at', today())
            ->first();

        return view('submit', compact('challenge', 'existingSubmission'));
    }

    /**
     * Layer 1+2+3: Validate, EXIF check, store, dispatch AI job.
     */
    public function store(Request $request, Challenge $challenge)
    {
        $user = auth()->user();

        // ── Prevent duplicate submissions ────────────────────────
        $existing = ChallengeSubmission::where('user_id', $user->id)
            ->where('challenge_id', $challenge->id)
            ->whereDate('created_at', today())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted this challenge today.');
        }

        // ── Layer 1: Validate file ────────────────────────────────
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:10240'],
        ]);

        $file = $request->file('photo');

        // ── Layer 2: EXIF Timestamp Check ─────────────────────────
        $exifTimestamp = null;
        $exifLat       = null;
        $exifLng       = null;
        $exifError     = null;

        try {
            $tmpPath = $file->getRealPath();
            $exif    = @exif_read_data($tmpPath);

            if ($exif && isset($exif['DateTimeOriginal'])) {
                $exifTimestamp = \Carbon\Carbon::createFromFormat(
                    'Y:m:d H:i:s',
                    $exif['DateTimeOriginal']
                );

                // Reject if older than 24 hours
                if ($exifTimestamp->diffInHours(now()) > 24) {
                    return back()->with('error',
                        'Photo was taken more than 24 hours ago. Please take a fresh photo of your eco action.'
                    );
                }
            }

            // Extract GPS
            if ($exif && isset($exif['GPSLatitude'])) {
                $exifLat = $this->gpsToDecimal($exif['GPSLatitude'], $exif['GPSLatitudeRef'] ?? 'N');
                $exifLng = $this->gpsToDecimal($exif['GPSLongitude'], $exif['GPSLongitudeRef'] ?? 'E');
            }
        } catch (\Throwable $e) {
            // EXIF not available on some images — not a hard failure
            $exifError = $e->getMessage();
        }

        // ── Store photo ───────────────────────────────────────────
        $photoPath = $file->store('submissions', 'public');

        // ── Create submission ─────────────────────────────────────
        $submission = ChallengeSubmission::create([
            'user_id'        => $user->id,
            'challenge_id'   => $challenge->id,
            'photo_path'     => $photoPath,
            'exif_timestamp' => $exifTimestamp,
            'exif_lat'       => $exifLat,
            'exif_lng'       => $exifLng,
            'status'         => 'pending_ai',
        ]);

        // ── Layer 3: Dispatch AI verification job (async) ─────────
        ProcessSubmissionAI::dispatch($submission);

        return redirect()->route('dashboard')
            ->with('success', 'Photo submitted! Our AI is verifying your action. Check the community feed soon.');
    }

    // ── Helpers ─────────────────────────────────────────────────────

    private function gpsToDecimal(array $coord, string $hemi): float
    {
        [$deg, $min, $sec] = array_map(function ($v) {
            [$n, $d] = explode('/', $v);
            return (float) $n / (float) ($d ?: 1);
        }, $coord);

        $decimal = $deg + ($min / 60) + ($sec / 3600);
        return in_array(strtoupper($hemi), ['S', 'W']) ? -$decimal : $decimal;
    }
}
