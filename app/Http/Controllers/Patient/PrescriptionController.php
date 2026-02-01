<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;

class PrescriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $patient = $user->patient;

        if (!$patient) {
            return view('patient.prescriptions.index', ['prescriptions' => collect([])]);
        }

        // Get prescriptions via patient appointments (linked by Patient Profile ID)
        $prescriptions = Prescription::whereHas('diagnosis.appointment', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->with(['diagnosis.appointment.doctor', 'diagnosis.appointment.department'])
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }

    public function requestRefill(Prescription $prescription)
    {
        $user = auth()->user();

        // Load necessary relationship if not loaded
        if (!$prescription->relationLoaded('diagnosis')) {
            $prescription->load('diagnosis.appointment');
        }

        // Verify ownership (Appointment Patient ID is User ID)
        if ((int)$prescription->diagnosis->appointment->patient_id !== (int)$user->id) {
            abort(403);
        }

        // Notify Doctor
        $doctor = $prescription->diagnosis->appointment->doctor;
        
        if ($doctor) {
            $doctor->notify(new \App\Notifications\RefillRequested($prescription, $user));
        }

        return back()->with('success', 'Refill request sent to your doctor.');
    }
}
