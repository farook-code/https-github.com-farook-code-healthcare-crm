<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\PatientVital;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = auth()->id();

        // 1. Statistics
        $todayAppointmentsCount = \App\Models\Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->count();
        
        $totalPatientsCount = \App\Models\User::whereHas('appointmentsAsPatient', function($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->distinct()->count();

        // 2. Today's Upcoming Schedule
        $todaySchedule = \App\Models\Appointment::with(['patient', 'department'])
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'completed')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        // 3. Recent Vitals (Keep existing logic)
        $recentVitals = PatientVital::with(['patient'])
            ->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.doctor', compact(
            'recentVitals', 
            'todayAppointmentsCount', 
            'totalPatientsCount',
            'todaySchedule'
        ));
    }
    public function patients()
    {
        $patients = \App\Models\User::whereHas('appointmentsAsPatient', function($q) {
            $q->where('doctor_id', auth()->id());
        })->distinct()->paginate(10);

        return view('doctors.patients', compact('patients'));
    }

    public function invoices(\Illuminate\Http\Request $request)
    {
         // 1. Base Query (Doctor's Invoices)
         $baseQuery = \App\Models\Invoice::whereHas('appointment', function ($q) {
            $q->where('doctor_id', auth()->id());
        });

        // Filter by Category
        if ($request->filled('category')) {
            $baseQuery->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('appointment.patient', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Stats
        $totalRevenue = (clone $baseQuery)->where('status', 'paid')->sum('amount');
        $pendingAmount = (clone $baseQuery)->where('status', 'pending')->sum('amount');
        $totalInvoices = (clone $baseQuery)->count();
        $paidInvoices = (clone $baseQuery)->where('status', 'paid')->count();

        // 3. List
        $invoices = (clone $baseQuery)
            ->with(['appointment.patient', 'items'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Reuse Admin View but ensure we have access (Role check handled by middleware)
        // We might need to adjust the view if it has admin-specific buttons (like "Create Invoice")
        return view('admin.invoices.index', [
            'invoices' => $invoices,
            'totalRevenue' => $totalRevenue,
            'pendingAmount' => $pendingAmount,
            'totalInvoices' => $totalInvoices,
            'paidInvoices' => $paidInvoices,
            'currentCategory' => $request->category,
        ]);
    }
}
