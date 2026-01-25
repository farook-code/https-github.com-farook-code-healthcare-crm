<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logChange($model, 'created');
        });

        static::updated(function ($model) {
            self::logChange($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logChange($model, 'deleted');
        });
    }

    protected static function logChange($model, $action)
    {
        if (!Auth::check()) return;

        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model_type' => get_class($model),
            'model_id'   => $model->id,
            'changes'    => $action === 'updated' ? $model->getChanges() : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
