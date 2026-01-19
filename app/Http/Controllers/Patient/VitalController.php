<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\PatientVital;

class VitalController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        abort_if(!$patient, 403);

        $vitals = PatientVital::where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patient.vitals.index', compact('vitals'));
    }
}
