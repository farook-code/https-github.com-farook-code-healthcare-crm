<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Department;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Basic Stats (Cached for 10 minutes)
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_stats', 600, function () {
            return [
                'users' => User::count(),
                'doctors' => DoctorProfile::count(),
                'departments' => Department::count(),
                'appointments' => Appointment::count(),
            ];
        });

        // 2. Recent Data (Always Real-time usually, or short cache)
        $recentUsers = User::with('role')->latest()->take(5)->get();
        $recentAppointments = Appointment::with(['doctor', 'patient'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Chart Data: Monthly Revenue (Cached for 1 hour)
        $chartData = \Illuminate\Support\Facades\Cache::remember('admin_chart_revenue', 3600, function () {
            $revenueData = [];
            $months = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthName = $date->format('M');
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                
                $monthlyTotal = Invoice::where('status', 'paid')
                    ->whereBetween('paid_at', [$start, $end])
                    ->sum('amount');
                
                $months[] = $monthName;
                $revenueData[] = $monthlyTotal;
            }
            return ['months' => $months, 'revenueData' => $revenueData];
        });

        // 4. Chart Data: Appointment Status (Cached for 10 mins)
        $appointmentStatus = \Illuminate\Support\Facades\Cache::remember('admin_chart_status', 600, function () {
            return [
                'completed' => Appointment::where('status', 'completed')->count(),
                'scheduled' => Appointment::where('status', 'scheduled')->count(),
                'cancelled' => Appointment::where('status', 'cancelled')->count(),
            ];
        });

        // 5. Billing Stats (Cached for 10 mins)
        $billingStats = \Illuminate\Support\Facades\Cache::remember('admin_billing_stats', 600, function () {
            // Check if column exists to prevent crash if migration not run
            $hasCategory = \Illuminate\Support\Facades\Schema::hasColumn('invoices', 'category');

            return [
                'totalRevenue' => Invoice::where('status', 'paid')->sum('amount'),
                'pendingAmount' => Invoice::where('status', 'pending')->sum('amount'),
                'totalInvoices' => Invoice::count(),
                'paidInvoices' => Invoice::where('status', 'paid')->count(),
                'pendingInvoices' => Invoice::where('status', 'pending')->count(),
                'categoryBreakdown' => $hasCategory ? [
                    'opd' => Invoice::where('status', 'paid')->where('category', 'opd')->sum('amount'),
                    'ipd' => Invoice::where('status', 'paid')->where('category', 'ipd')->sum('amount'),
                    'pharmacy' => Invoice::where('status', 'paid')->where('category', 'pharmacy')->sum('amount'),
                    'lab' => Invoice::where('status', 'paid')->where('category', 'lab')->sum('amount'),
                ] : [],
            ];
        });

        // 6. Recent Invoices (Real-time)
        $recentInvoices = Invoice::with(['patient'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.admin', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentAppointments' => $recentAppointments,
            'months' => $chartData['months'],
            'revenueData' => $chartData['revenueData'],
            'appointmentStatus' => $appointmentStatus,
            'billingStats' => $billingStats,
            'recentInvoices' => $recentInvoices,
        ]);
    }
}
