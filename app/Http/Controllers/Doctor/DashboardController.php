<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\PatientVital;

class DashboardController extends Controller
{
    public function index()
    {
        $doctorId = auth()->id();

        $recentVitals = PatientVital::with(['patient'])
            ->whereHas('appointment', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.doctor', compact('recentVitals'));
    }
}
