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
        $user = auth()->user();
        // Allow assigned Doctor OR any Nurse
        if ($user->role->slug !== 'nurse' && $appointment->doctor_id !== $user->id) {
            abort(403);
        }

        $appointment->load([
            'patient.patient',
            'diagnosis.prescriptions',
            'vitals.recorder',
        ]);

        $patientProfile = $appointment->patient->patient;

        return view('doctors.appointments.show', [
            'appointment' => $appointment,
            'vaccinations' => $patientProfile ? $patientProfile->vaccinations()->latest('administered_date')->get() : collect(),
            'history' => Appointment::with(['doctor', 'diagnosis'])
                ->where('patient_id', $appointment->patient_id)
                ->where('id', '!=', $appointment->id) // Exclude current
                ->where('status', 'completed') // Only completed visits
                ->latest('appointment_date')
                ->get()
        ]);
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

        // Notify Patient (Email)
        if ($appointment->patient && $appointment->patient->user) {
             $appointment->patient->user->notify(
                 new \App\Notifications\AppointmentStatusUpdated($appointment)
             );

             // ✅ Auto-Message to Patient (Chat)
             \App\Services\ChatSystemMessage::send(
                 auth()->id(), // Sender: Doctor
                 $appointment->patient->user->id, // Receiver: Patient
                 "Your appointment dated {$appointment->appointment_date->format('M d, Y')} has been marked as completed. You can view your visit summary and prescriptions in your dashboard."
             );
        }

        return back()->with('success', 'Appointment completed and status update email sent to patient.');
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
            'outcome'   => 'nullable|string',
        ]);

        $appointment->diagnosis()->create([
            'doctor_id'  => auth()->id(),
            'patient_id' => $appointment->patient_id,
            'symptoms'   => $request->symptoms,
            'diagnosis'  => $request->diagnosis,
            'notes'      => $request->notes,
            'outcome'    => $request->outcome,
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
    public function storePrescription(Request $request, Diagnosis $diagnosis, \App\Services\DrugInteractionService $interactionService)
    {
        $request->validate([
            'medicine_name' => 'required|string',
            'dosage'        => 'required|string',
            'duration'      => 'nullable|string',
            'instructions'  => 'nullable|string',
        ]);

        // 1. Fetch existing medicines for this diagnosis context (or patient history if advanced)
        $existingMedicines = $diagnosis->prescriptions()->pluck('medicine_name')->toArray();

        // 2. Check for interactions
        $warnings = $interactionService->checkInteraction($request->medicine_name, $existingMedicines);

        $diagnosis->prescriptions()->create([
            'medicine_name' => $request->medicine_name,
            'dosage'        => $request->dosage,
            'duration'      => $request->duration,
            'instructions'  => $request->instructions,
        ]);

        if (!empty($warnings)) {
            // Format warning message
            $msg = "Warning: Interaction detected! ";
            foreach ($warnings as $w) {
                $msg .= "{$w['drug_a']} + {$w['drug_b']} -> {$w['severity']} ";
            }
            return back()->with('warning', $msg);
        }

        return back()->with('success', 'Prescription added successfully');
    }
    public function printPrescription(Diagnosis $diagnosis)
    {
        // Allow Doctor OR Patient (if it's their own) to view
        // For now strict to Doctor
        abort_if($diagnosis->doctor_id !== auth()->id(), 403);

        $diagnosis->load(['patient', 'prescriptions', 'doctor.doctorProfile.department']);

        return view('doctors.prescriptions.print', compact('diagnosis'));
    }

    /**
     * Store vaccination record
     */
    public function storeVaccination(Request $request, Appointment $appointment)
    {
        // Allow Doctor or Nurse
        if (auth()->user()->role->slug !== 'nurse' && auth()->user()->role->slug !== 'doctor') {
            abort(403);
        }

        $request->validate([
            'vaccine_name' => 'required|string',
            'dose_number' => 'nullable|string',
            'administered_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if (!$appointment->patient->patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        $appointment->patient->patient->vaccinations()->create([
            'vaccine_name' => $request->vaccine_name,
            'dose_number' => $request->dose_number,
            'administered_date' => $request->administered_date,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Vaccination record added.');
    }
}
