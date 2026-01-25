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
            'endDate'
        ));
    }
}
