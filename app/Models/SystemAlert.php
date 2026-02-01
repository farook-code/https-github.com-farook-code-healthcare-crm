<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAlert extends Model
{
    protected $fillable = [
        'type', // critical, warning, info
        'message',
        'source_type',
        'source_id',
        'is_resolved',
        'resolved_at',
        'resolved_by'
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function source()
    {
        return $this->morphTo();
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_resolved', false);
    }
    
    public function scopeCritical($query)
    {
        return $query->where('type', 'critical');
    }
}
