<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache duration constants (in seconds)
     */
    const SHORT_DURATION = 300;      // 5 minutes
    const MEDIUM_DURATION = 1800;    // 30 minutes
    const LONG_DURATION = 3600;      // 1 hour
    const DAY_DURATION = 86400;      // 24 hours

    /**
     * Get or set cached departments list
     */
    public static function departments()
    {
        return Cache::remember('departments:active', self::LONG_DURATION, function () {
            return \App\Models\Department::where('status', 'active')
                ->select('id', 'name', 'description')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get or set cached doctors list
     */
    public static function doctors()
    {
        return Cache::remember('doctors:active', self::MEDIUM_DURATION, function () {
            return \App\Models\User::where('role_id', function($query) {
                $query->select('id')
                    ->from('roles')
                    ->where('slug', 'doctor')
                    ->limit(1);
            })
            ->where('status', 'active')
            ->with('doctorProfile:user_id,specialization')
            ->select('id', 'name', 'email')
            ->get();
        });
    }

    /**
     * Get or set user permissions
     */
    public static function userPermissions($userId)
    {
        return Cache::remember("user:permissions:{$userId}", self::LONG_DURATION, function () use ($userId) {
            $user = \App\Models\User::with('role:id,slug,name')->find($userId);
            return [
                'role' => $user->role?->slug,
                'role_name' => $user->role?->name,
                'can_manage_appointments' => in_array($user->role?->slug, ['admin', 'super-admin', 'reception', 'doctor']),
                'can_view_reports' => in_array($user->role?->slug, ['admin', 'super-admin']),
                'can_manage_users' => in_array($user->role?->slug, ['admin', 'super-admin']),
            ];
        });
    }

    /**
     * Get or set patient statistics
     */
    public static function patientStats($patientId)
    {
        return Cache::remember("patient:stats:{$patientId}", self::SHORT_DURATION, function () use ($patientId) {
            return [
                'total_appointments' => \App\Models\Appointment::where('patient_id', $patientId)->count(),
                'completed_appointments' => \App\Models\Appointment::where('patient_id', $patientId)->where('status', 'completed')->count(),
                'total_invoices' => \App\Models\Invoice::where('patient_id', $patientId)->count(),
                'pending_payments' => \App\Models\Invoice::where('patient_id', $patientId)->where('status', 'unpaid')->sum('total_amount'),
            ];
        });
    }

    /**
     * Get or set dashboard statistics (admin/doctor)
     */
    public static function dashboardStats($userId, $role)
    {
        return Cache::remember("dashboard:stats:{$role}:{$userId}", self::SHORT_DURATION, function () use ($userId, $role) {
            $stats = [];

            if (in_array($role, ['admin', 'super-admin'])) {
                $stats = [
                    'total_patients' => \App\Models\Patient::count(),
                    'today_appointments' => \App\Models\Appointment::whereDate('appointment_date', today())->count(),
                    'pending_invoices' => \App\Models\Invoice::where('status', 'unpaid')->count(),
                    'low_stock_medicines' => \App\Models\Medicine::where('stock_quantity', '<=', 10)->count(),
                ];
            } elseif ($role === 'doctor') {
                $stats = [
                    'today_appointments' => \App\Models\Appointment::where('doctor_id', $userId)->whereDate('appointment_date', today())->count(),
                    'pending_appointments' => \App\Models\Appointment::where('doctor_id', $userId)->where('status', 'scheduled')->count(),
                    'completed_this_month' => \App\Models\Appointment::where('doctor_id', $userId)
                        ->where('status', 'completed')
                        ->whereMonth('appointment_date', now()->month)
                        ->count(),
                ];
            }

            return $stats;
        });
    }

    /**
     * Clear all caches
     */
    public static function clearAll()
    {
        Cache::flush();
    }

    /**
     * Clear specific cache
     */
    public static function forget($key)
    {
        Cache::forget($key);
    }

    /**
     * Clear user-specific caches
     */
    public static function clearUserCache($userId)
    {
        Cache::forget("user:permissions:{$userId}");
        Cache::forget("patient:stats:{$userId}");
        // Add more user-related cache keys as needed
    }

    /**
     * Clear department cache
     */
    public static function clearDepartments()
    {
        Cache::forget('departments:active');
    }

    /**
     * Clear doctors cache
     */
    public static function clearDoctors()
    {
        Cache::forget('doctors:active');
    }
}
