<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'feed_id',
        'user_id',
        'parent_id',
        'content',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->latest();
    }

    public function getFormattedContentAttribute()
    {
        if (!$this->content) return null;
        
        // Escape HTML to prevent XSS
        $text = htmlspecialchars($this->content, ENT_QUOTES, 'UTF-8');
        
        // Wrap @username in a styled span
        $text = preg_replace('/@(\w+)/', '<span class="text-green-600 font-bold hover:underline cursor-pointer">@$1</span>', $text);
        
        return $text;
    }
}
