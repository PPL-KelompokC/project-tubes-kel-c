<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'location',
        'avatar',
        'points',
        'pending_points',
        'streak',
        'longest_streak',
        'last_active_date',
        'carbon_saved',
        'challenges_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'last_active_date'  => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'user_challenges')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Removed unused features relations

    public function submissions()
    {
        return $this->hasMany(ChallengeSubmission::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // ── Streak Logic ───────────────────────────────────────────────

    /**
     * Update the streak counter. Call after a verified submission.
     */
    public function updateStreak(): void
    {
        $today     = today();
        $yesterday = today()->subDay();

        if ($this->last_active_date && $this->last_active_date->equalTo($today)) {
            return; // Already counted today
        }

        if ($this->last_active_date && $this->last_active_date->equalTo($yesterday)) {
            $this->streak++;
        } else {
            $this->streak = 1; // Reset
        }

        if ($this->streak > $this->longest_streak) {
            $this->longest_streak = $this->streak;
        }

        $this->last_active_date = $today;
        $this->save();
    }
}
