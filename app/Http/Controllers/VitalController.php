<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\PatientVital;
use Illuminate\Http\Request;

class VitalController extends Controller
{
    public function create(Appointment $appointment)
    {
        return view('vitals.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string',
            'pulse' => 'nullable|integer',
            'temperature' => 'nullable|numeric',
            'oxygen_level' => 'nullable|integer',
            'notes' => 'nullable|string',
        ]);

        $patientProfile = $appointment->patient->patient;

        if (!$patientProfile) {
            return back()->with('error', 'Patient medical profile not found. Please create a patient profile first.');
        }

        PatientVital::create([
            'patient_id' => $patientProfile->id,
            'appointment_id' => $appointment->id,
            'recorded_by' => auth()->id(),
            ...$request->only([
                'height',
                'weight',
                'blood_pressure',
                'pulse',
                'temperature',
                'oxygen_level',
                'notes',
            ]),
        ]);

        return redirect()->back()->with('success', 'Vitals recorded successfully');
    }
}
