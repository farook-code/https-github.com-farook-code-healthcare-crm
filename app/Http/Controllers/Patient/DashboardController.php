<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        abort_if(!$patient, 403, 'Patient profile not found');

        return view('patient.dashboard', compact('patient'));
    }
}
