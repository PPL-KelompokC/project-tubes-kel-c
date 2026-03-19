<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionVerification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'submission_id',
        'user_id',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ChallengeSubmission::class, 'submission_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
