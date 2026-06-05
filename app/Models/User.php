<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\StreakMilestone;
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
        'referral_code',
        'referred_by',
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

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

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

<<<<<<< HEAD
=======
    public function joinedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user');
    }

>>>>>>> b7f29169b8d5ed13033c75472146d88a00b480d3
    public function feeds()
    {
        return $this->hasMany(Feed::class);
    }

    public function rewardTransactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }

    // ── Streak Logic ───────────────────────────────────────────────

    /**
     * Get the current streak, resetting it if it has been broken.
     */
    public function getStreakAttribute($value)
    {
        $today = today();
        $yesterday = today()->subDay();

        // If the user was never active, streak is 0
        if (!$this->last_active_date) {
            return 0;
        }

        // If last active was today or yesterday, streak is valid
        if ($this->last_active_date->equalTo($today) || $this->last_active_date->equalTo($yesterday)) {
            return $value;
        }

        // Otherwise, streak is broken. Reset it in the DB and return 0
        // We use a separate update to avoid infinite recursion if this accessor is called during save
        if ($value > 0) {
            $this->attributes['streak'] = 0;
            $this->save();
        }

        return 0;
    }

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

        // If they were active yesterday, increment. Otherwise, it's a new streak of 1.
        if ($this->last_active_date && $this->last_active_date->equalTo($yesterday)) {
            $this->streak = ($this->attributes['streak'] ?? 0) + 1;
        } else {
            $this->streak = 1;
        }

        if ($this->streak > ($this->longest_streak ?? 0)) {
            $this->longest_streak = $this->streak;
        }

        $this->last_active_date = $today;
        $this->save();

        // Check for streak milestones
        $milestones = [7, 14, 30, 50, 100];
        if (in_array($this->attributes['streak'], $milestones)) {
            $this->notify(new StreakMilestone($this->attributes['streak']));
        }
    }
}
