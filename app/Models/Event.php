<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'type',
        'date',
        'participants',
        'x',
        'y',
        'description',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendingUsers()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }
}
