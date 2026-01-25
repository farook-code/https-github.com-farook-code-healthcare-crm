<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'invoice'])
            ->whereDate('appointment_date', now()->toDateString())
            ->orderBy('appointment_time')
            ->get();

        return view('dashboards.nurse', compact('appointments'));
    }
}
