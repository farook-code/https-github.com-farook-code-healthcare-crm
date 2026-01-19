<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\PatientVital;
use Illuminate\Http\Request;

class DoctorAppointmentController extends Controller
{
    /**
     * Show appointment details
     */
    public function show(Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->id(), 403);

        $appointment->load([
            'patient',
            'diagnosis.prescriptions',
            'vitals.recorder',
        ]);

        return view('doctors.appointments.show', compact('appointment'));
    }

    /**
     * Mark appointment as completed
     */
    public function complete(Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->id(), 403);

        $appointment->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Appointment completed');
    }

    /**
     * Diagnosis form
     */
    public function diagnosisForm(Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->id(), 403);

        return view('doctors.appointments.diagnosis', compact('appointment'));
    }

    /**
     * Store diagnosis
     */
    public function storeDiagnosis(Request $request, Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->id(), 403);

        $request->validate([
            'symptoms'  => 'nullable|string',
            'diagnosis' => 'required|string',
            'notes'     => 'nullable|string',
        ]);

        $appointment->diagnosis()->create([
            'doctor_id'  => auth()->id(),
            'patient_id' => $appointment->patient_id,
            'symptoms'   => $request->symptoms,
            'diagnosis'  => $request->diagnosis,
            'notes'      => $request->notes,
        ]);

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('success', 'Diagnosis added successfully');
    }

    /**
     * Prescription form
     */
    public function prescriptionForm(Diagnosis $diagnosis)
    {
        abort_if($diagnosis->doctor_id !== auth()->id(), 403);

        return view('doctors.prescriptions.create', compact('diagnosis'));
    }

    /**
     * Store prescription
     */
    public function storePrescription(Request $request, Diagnosis $diagnosis)
    {
        $request->validate([
            'medicine_name' => 'required|string',
            'dosage'        => 'required|string',
            'duration'      => 'nullable|string',
            'instructions'  => 'nullable|string',
        ]);

        $diagnosis->prescriptions()->create([
            'medicine_name' => $request->medicine_name,
            'dosage'        => $request->dosage,
            'duration'      => $request->duration,
            'instructions'  => $request->instructions,
        ]);

        return back()->with('success', 'Prescription added successfully');
    }
}
