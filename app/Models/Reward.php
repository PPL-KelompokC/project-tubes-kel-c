<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'stock',
        'status',
        'category',
        'image',
    ];

    public function transactions()
    {
        return $this->hasMany(RewardTransaction::class);
    }
}
