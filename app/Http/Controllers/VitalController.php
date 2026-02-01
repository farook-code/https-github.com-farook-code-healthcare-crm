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

        // Robust lookup: Try relationship first, then direct query
        $patientProfile = $appointment->patient->patient ?? \App\Models\Patient::where('user_id', $appointment->patient_id)->first();

        // Auto-fix: If User exists but Profile is missing, create a barebones profile
        if (!$patientProfile) {
            $user = \App\Models\User::find($appointment->patient_id);
            if ($user) {
                $patientProfile = \App\Models\Patient::create([
                    'user_id' => $user->id,
                    'patient_code' => 'PT-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => true
                ]);
            }
        }

        if (!$patientProfile) {
             return back()->with('error', 'Critical Error: Patient user not found. cannot record vitals.');
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
