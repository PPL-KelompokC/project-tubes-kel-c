<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Challenge extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'difficulty',
        'points',
        'co2_saved',
        'image_url',
        'is_daily',
        'active_date',
    ];

    protected $casts = [
        'is_daily'    => 'boolean',
        'active_date' => 'date',
        'co2_saved'   => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_challenges')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    // ── Scopes ────────────────────────────────────────────────────

    /** Today's daily challenges */
    public function scopeToday(Builder $query): Builder
    {
        return $query->where('is_daily', true)
                     ->where('active_date', today());
    }

    /** Count how many users have a verified/pending_admin/rejected submission today */
    public function participantCount(): int
    {
        return $this->submissions()
                    ->whereDate('created_at', today())
                    ->whereIn('status', ['pending_admin', 'verified', 'rejected'])
                    ->count();
    }
}
