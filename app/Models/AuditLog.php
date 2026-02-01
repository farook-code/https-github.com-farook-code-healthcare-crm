<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\MassPrunable;

class AuditLog extends Model
{
    use MassPrunable;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    /**
     * Get the prunable model query.
     */
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subYear());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo('model');
    }
    public function getDetailsAttribute()
    {
        $modelName = class_basename($this->model_type);
        if ($this->action === 'updated') {
            $changedFields = is_array($this->changes) ? implode(', ', array_keys($this->changes)) : 'records';
            return "Updated {$modelName} #{$this->model_id} ($changedFields)";
        }
        return ucfirst($this->action) . " {$modelName} #{$this->model_id}";
    }
}
