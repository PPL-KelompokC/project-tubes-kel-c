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
        'status',
        'points_awarded',
        'verified_at',
        'rejection_reason',
    ];

    protected $casts = [
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

    /** Status label for UI */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_admin'  => 'Awaiting Admin Review',
            'verified'       => 'Verified',
            'rejected'       => 'Rejected',
            default          => ucfirst($this->status),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'pending_admin' => 'text-yellow-700 bg-yellow-100',
            'verified'      => 'text-green-700 bg-green-100',
            'rejected'      => 'text-red-700 bg-red-100',
            default         => 'text-gray-600 bg-gray-100',
        };
    }
}
