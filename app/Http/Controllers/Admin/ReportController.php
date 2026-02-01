<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date Filter
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Helper to apply date filter to a query
        $applyDateFilter = function($query, $column = 'created_at') use ($startDate, $endDate) {
            if ($startDate) $query->whereDate($column, '>=', $startDate);
            if ($endDate) $query->whereDate($column, '<=', $endDate);
            return $query;
        };

        // 1. Appointment Stats
        $apptQuery = \App\Models\Appointment::query();
        $applyDateFilter($apptQuery, 'appointment_date');
        
        $totalAppointments = $apptQuery->count();
        
        // Check for subscription feature
        // 'analytics_advanced' is present in Premium (Small Clinic), Pro (Hospital), and Enterprise
        $hasAdvancedAccess = auth()->user()->hasFeature('analytics_advanced') || auth()->user()->hasFeature('module_audit_logs');

        $totalRevenue = 0;
        $pendingRevenue = 0;
        $appointmentsByStatus = [];
        $revenueTrend = collect([]);
        $deptStats = collect([]);

        if ($hasAdvancedAccess) {
             // Clone query for breakdown to avoid carrying over aggregate
            $breakdownQuery = \App\Models\Appointment::query();
            $applyDateFilter($breakdownQuery, 'appointment_date');
            $appointmentsByStatus = $breakdownQuery->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            // 2. Revenue Stats
            $revQuery = \App\Models\Invoice::where('status', 'paid');
            $applyDateFilter($revQuery, 'paid_at');
            $totalRevenue = $revQuery->sum('amount');
            
            $pendingQuery = \App\Models\Invoice::where('status', 'pending');
            $applyDateFilter($pendingQuery, 'issued_at');
            $pendingRevenue = $pendingQuery->sum('amount');

            // Revenue Trend (Last 30 Days or Selected Range)
            $trendQuery = \App\Models\Invoice::where('status', 'paid')
                ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
                ->groupBy('date')
                ->orderBy('date');
            $applyDateFilter($trendQuery, 'paid_at');
            $revenueTrend = $trendQuery->get();

            // Department Distribution
            $deptQuery = \App\Models\Appointment::join('departments', 'appointments.department_id', '=', 'departments.id')
                ->selectRaw('departments.name as name, count(*) as count')
                ->groupBy('departments.name');
             $applyDateFilter($deptQuery, 'appointments.appointment_date');
             $deptStats = $deptQuery->pluck('count', 'name');
        }

        // 3. Patient Stats (Patients registered in this period)
        $patientQuery = \App\Models\Patient::query();
        $applyDateFilter($patientQuery, 'created_at');
        $totalPatients = $patientQuery->count();

        // 3. Recent Activity (Latest 5 appointments)
        $recentAppointments = \App\Models\Appointment::with(['patient', 'doctor'])
            ->latest();
            
        if ($startDate) $recentAppointments->whereDate('appointment_date', '>=', $startDate);
        if ($endDate) $recentAppointments->whereDate('appointment_date', '<=', $endDate);
            
        $recentAppointments = $recentAppointments->take(5)->get();

        return view('admin.reports.index', compact(
            'totalAppointments',
            'appointmentsByStatus',
            'totalRevenue',
            'pendingRevenue',
            'recentAppointments',
            'totalPatients',
            'startDate', 
            'endDate',
            'hasAdvancedAccess',
            'revenueTrend',
            'deptStats'
        ));
    }
}
