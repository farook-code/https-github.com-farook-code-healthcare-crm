<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        abort_if(!$patient, 403, 'Patient profile not found');

        // Appointments (User ID relation)
        $appointments = auth()->user()->appointmentsAsPatient()
            ->with('doctor', 'invoice')
            ->latest()
            ->take(5)
            ->get();

        // Latest Vitals (Profile ID relation)
        $latestVitals = \App\Models\PatientVital::where('patient_id', $patient->id)
            ->latest()
            ->first();

        // Recent Prescriptions (User ID relation via Diagnosis)
        $prescriptions = \App\Models\Prescription::whereHas('diagnosis', function($q) use ($patient) {
            $q->where('patient_id', $patient->user_id);
        })->with('diagnosis.doctor.doctorProfile')->latest()->take(5)->get();

        // Recent Lab Reports (Profile ID relation)
        $labReports = \App\Models\LabReport::where('patient_id', $patient->id)
            ->latest()
            ->take(5)
            ->get();

        return view('patient.dashboard', compact('patient', 'appointments', 'latestVitals', 'prescriptions', 'labReports'));
    }
}
