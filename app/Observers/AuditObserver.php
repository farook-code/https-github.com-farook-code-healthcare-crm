<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $this->log($model, 'created');
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        // Only log if there are changes
        if(empty($model->getChanges())) {
             return;
        }
        
        // Ignore updated_at specific updates if needed, but best to keep
        $this->log($model, 'updated', [
            'old' => $model->getOriginal(),
            'new' => $model->getChanges(),
        ]);
    }

    /**
     * Handle the Model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted', [
            'old' => $model->attributesToArray()
        ]);
    }

    protected function log(Model $model, string $action, array $details = [])
    {
        // Avoid logging AuditLogs themselves or other internal models if needed
        if ($model instanceof AuditLog) {
            return;
        }

        try {
            AuditLog::create([
                'user_id'    => Auth::id(), // Might be null for system jobs
                'action'     => $action,
                'model_type' => get_class($model),
                'model_id'   => $model->id,
                'changes'    => !empty($details) ? $details : null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::header('User-Agent'),
            ]);
        } catch (\Exception $e) {
            // Fail silently to avoid breaking the app flow due to logging errors
            // But in production you might want to log this to error log
            \Illuminate\Support\Facades\Log::error("Audit Log Error: " . $e->getMessage());
        }
    }
}
