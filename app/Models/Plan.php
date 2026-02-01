<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'duration_in_days',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];
}
