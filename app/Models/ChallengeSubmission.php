<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChallengeSubmission extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'photo_path',
        'exif_timestamp',
        'exif_lat',
        'exif_lng',
        'ai_score',
        'ai_labels',
        'status',
        'points_awarded',
        'verified_at',
    ];

    protected $casts = [
        'ai_labels'      => 'array',
        'exif_timestamp' => 'datetime',
        'verified_at'    => 'datetime',
        'exif_lat'       => 'decimal:7',
        'exif_lng'       => 'decimal:7',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(SubmissionVerification::class, 'submission_id');
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function verifyCount(): int
    {
        return $this->verifications()->where('type', 'verify')->count();
    }

    public function reportCount(): int
    {
        return $this->verifications()->where('type', 'report')->count();
    }

    /** Check if a given user has already voted on this submission */
    public function userVote(int $userId): ?string
    {
        $vote = $this->verifications()->where('user_id', $userId)->first();
        return $vote?->type;
    }

    /** Status label for UI */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_ai'     => 'Processing…',
            'manual_review'  => 'Awaiting Admin Review',
            'verified'       => 'Verified',
            'rejected'       => 'Rejected',
            default          => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending_ai'    => 'text-gray-500 bg-gray-100',
            'manual_review' => 'text-yellow-700 bg-yellow-100',
            'verified'      => 'text-green-700 bg-green-100',
            'rejected'      => 'text-red-700 bg-red-100',
            default         => 'text-gray-600 bg-gray-100',
        };
    }
}
