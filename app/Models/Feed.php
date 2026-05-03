<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'media',
        'status',
        'likes_count',
        'comments_count',
        'feed_type',
    ];

    protected $casts = [
        'media' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────

    /**
     * Get the user that created the feed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments for the feed post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes for the feed post
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────

    /**
     * Scope to get only active feeds
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only hidden feeds
     */
    public function scopeHidden($query)
    {
        return $query->where('status', 'hidden');
    }

    /**
     * Scope to get all feeds ordered by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ── Methods ────────────────────────────────────────────────────

    /**
     * Hide the feed post
     */
    public function hide()
    {
        $this->update(['status' => 'hidden']);
    }

    /**
     * Show the hidden feed post
     */
    public function show()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Format creation timestamp for display
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    /**
     * Get relative time (e.g., "2 hours ago")
     */
    public function getRelativeTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
