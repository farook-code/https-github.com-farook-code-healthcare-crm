<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = ['ward_id', 'bed_number', 'type', 'daily_charge', 'is_available', 'status'];

    protected $casts = [
        'is_available' => 'boolean',
        'daily_charge' => 'decimal:2',
    ];

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function currentAdmission()
    {
        return $this->hasOne(IpdAdmission::class)->where('status', 'admitted')->latest();
    }
}
