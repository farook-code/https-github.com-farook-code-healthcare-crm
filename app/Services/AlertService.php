<?php

namespace App\Services;

use App\Models\SystemAlert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AlertService
{
    /**
     * Trigger a new system alert.
     *
     * @param string $type critical|warning|info
     * @param string $message
     * @param Model|null $source The model that caused the alert (e.g., Medicine, LabReport)
     * @return SystemAlert
     */
    public static function trigger(string $type, string $message, ?Model $source = null): SystemAlert
    {
        // Deduplicate: Don't create duplicate active alerts for same source/message
        $existing = SystemAlert::where('is_resolved', false)
            ->where('message', $message)
            ->when($source, function($q) use ($source) {
                return $q->where('source_type', get_class($source))
                         ->where('source_id', $source->id);
            })
            ->first();

        if ($existing) {
            return $existing;
        }

        Log::channel('daily')->info("System Alert Triggered: [$type] $message");

        $clinicId = null;
        if ($source && isset($source->clinic_id)) {
            $clinicId = $source->clinic_id;
        } elseif (auth()->check()) {
            $clinicId = auth()->user()->clinic_id;
        }

        return SystemAlert::create([
            'clinic_id' => $clinicId,
            'type' => $type,
            'message' => $message,
            'source_type' => $source ? get_class($source) : null,
            'source_id' => $source ? $source->id : null,
        ]);
    }

    /**
     * Resolve an alert.
     */
    public static function resolve(int $alertId, int $userId): bool
    {
        $alert = SystemAlert::find($alertId);
        if (!$alert) return false;

        $alert->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId
        ]);

        return true;
    }
}
