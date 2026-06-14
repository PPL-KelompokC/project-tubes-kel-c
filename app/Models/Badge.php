<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'category',
        'level',
        'description',
        'is_active',
    ];
}