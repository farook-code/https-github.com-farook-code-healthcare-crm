<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.analytics.index'); 
    }

    public function getData(Request $request)
    {
        $range = $request->get('range', '30_days');
        
        $dateFrom = match($range) {
            '7_days' => now()->subDays(7),
            '30_days' => now()->subDays(30),
            '90_days' => now()->subDays(90),
            'this_year' => now()->startOfYear(),
            default => now()->subDays(30),
        };

        // Revenue Chart (Daily)
        $revenue = Invoice::where('status', 'paid')
            ->where('created_at', '>=', $dateFrom)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Appointments Status Distribution
        $appointmentStatus = Appointment::where('created_at', '>=', $dateFrom)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Top Doctors by Revenue
         $topDoctors = Appointment::where('appointments.created_at', '>=', $dateFrom)
            ->whereHas('invoice', function($q) {
                $q->where('status', 'paid');
            })
            ->join('invoices', 'appointments.id', '=', 'invoices.appointment_id')
            ->join('users', 'appointments.doctor_id', '=', 'users.id')
            ->select(
                'users.name',
                DB::raw('SUM(invoices.total_amount) as revenue'),
                DB::raw('COUNT(appointments.id) as sessions')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();
            
        // Patient Growth
        $patients = Patient::where('created_at', '>=', $dateFrom)
             ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date') 
            ->get();

        return response()->json([
            'revenue' => [
                'labels' => $revenue->pluck('date'),
                'data' => $revenue->pluck('total'),
            ],
            'status' => [
                'labels' => $appointmentStatus->pluck('status'),
                'data' => $appointmentStatus->pluck('total'),
            ],
            'doctors' => [
                'labels' => $topDoctors->pluck('name'),
                'data' => $topDoctors->pluck('revenue'),
                'sessions' => $topDoctors->pluck('sessions'),
            ],
            'patients' => [
                'labels' => $patients->pluck('date'),
                'data' => $patients->pluck('total'),
            ]
        ]);
    }
}
