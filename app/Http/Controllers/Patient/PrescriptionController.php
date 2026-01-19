<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;

class PrescriptionController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        abort_if(!$patient, 403, 'Patient profile not found');

        // Get prescriptions via patient appointments
        $prescriptions = Prescription::whereHas('diagnosis.appointment', function ($query) use ($patient) {
                $query->where('patient_id', $patient->id);
            })
            ->latest()
            ->paginate(10);

        return view('patient.prescriptions.index', compact('prescriptions'));
    }
}
