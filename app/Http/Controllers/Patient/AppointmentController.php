<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Show patient's appointments list
     */
    public function index()
    {
        $patient = auth()->user()->patient;

        abort_if(!$patient, 403, 'Patient profile not found');

        $appointments = Appointment::where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Show single appointment (read-only)
     */
    public function show(Appointment $appointment)
    {
        $patient = auth()->user()->patient;

        abort_if(!$patient, 403, 'Patient profile not found');

        // Ensure appointment belongs to this patient
        abort_if($appointment->patient_id !== $patient->id, 403);

        $appointment->load([
           'patient',
    'doctor',
    'diagnosis.prescriptions',
    'vitals.recorder',
        ]);

        return view('patient.appointments.show', compact('appointment'));
    }
}
