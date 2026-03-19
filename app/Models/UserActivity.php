<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'activity_date',
        'points_earned',
        'co2_saved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
