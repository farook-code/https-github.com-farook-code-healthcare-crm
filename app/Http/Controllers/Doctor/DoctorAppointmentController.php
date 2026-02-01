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
     * List appointments
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'department'])
            ->where('doctor_id', auth()->id())
            ->whereHas('patient')
            // Sort Actionable items first (In Progress > Scheduled > Completed > Cancelled)
            ->orderByRaw("CASE 
                WHEN status = 'in_progress' THEN 1 
                WHEN status = 'scheduled' THEN 2 
                WHEN status = 'completed' THEN 3 
                ELSE 4 END")
            // Then sort by Date (Soonest first for active, Latest first for others potentially, but simple ASC is good for schedule)
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->paginate(10);

        return view('doctors.appointments.index', compact('appointments'));
    }

    public function calendar()
    {
        return view('doctors.appointments.calendar');
    }

    public function events(Request $request)
    {
        // For FullCalendar
        $start = $request->start;
        $end = $request->end;

        $events = Appointment::where('doctor_id', auth()->id())
            ->whereBetween('appointment_date', [$start, $end])
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient->name ?? 'Unknown',
                    'start' => $appointment->appointment_date->format('Y-m-d') . 'T' . $appointment->appointment_time,
                    'url' => route('doctor.appointments.show', $appointment),
                    'color' => match($appointment->status) {
                        'completed' => '#10B981', // green
                        'cancelled' => '#EF4444', // red
                        'in_progress' => '#8B5CF6', // purple
                        default => '#3B82F6' // blue (scheduled)
                    }
                ];
            });

        return response()->json($events);
    }

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
            'patient',
            'diagnosis.prescriptions',
            'vitals.recorder',
        ]);

        $patientProfile = $appointment->patient;

        return view('doctors.appointments.show', [
            'appointment' => $appointment,
            'vaccinations' => $patientProfile ? $patientProfile->vaccinations()->latest('administered_date')->get() : collect(),
            'history' => Appointment::with(['doctor', 'diagnosis'])
                ->where('patient_id', $appointment->patient_id)
                ->where('id', '!=', $appointment->id) // Exclude current
                ->where('status', 'completed') // Only completed visits
                ->latest('appointment_date')
                ->get(),
            'labTests' => \App\Models\LabTest::orderBy('name')->get(),
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
                 $appointment->patient->user_id, // Receiver: Patient User ID
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
            'recommended_action' => 'nullable|string',
        ]);

        // Create or Update Diagnosis
        $diagnosis = $appointment->diagnosis()->updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'doctor_id'  => auth()->id(),
                'patient_id' => $appointment->patient_id,
                'symptoms'   => $request->symptoms,
                'diagnosis'  => $request->diagnosis,
                'notes'      => $request->notes,
                'outcome'    => $request->outcome,
                'follow_up_action' => $request->recommended_action,
            ]
        );

        \Log::info('DoctorAppointmentController: Checking notification trigger', [
            'filled' => $request->filled('recommended_action'),
            'value' => $request->recommended_action
        ]);

        // Notify Receptionists if significant action recommended
        if ($request->filled('recommended_action') && 
           ($request->recommended_action === 'Suggest Admission (IPD)' || 
            $request->recommended_action === 'Suggest Surgery (OT)' ||
            $request->recommended_action === 'OPD Follow-up')) {
            
            $receptionists = \App\Models\User::whereHas('role', function($q) {
                $q->whereIn('slug', ['reception', 'admin']);
            })->get();

            \Log::info('DoctorAppointmentController: Found receptionists to notify', ['count' => $receptionists->count()]);

            $action = $request->recommended_action;
            $patientName = $appointment->patient->name;
            $doctorName = auth()->user()->name;

            foreach ($receptionists as $receptionist) {
                \Log::info("DoctorAppointmentController: Notifying user {$receptionist->id}");
                $receptionist->notify(new \App\Notifications\DoctorActionNotification(
                    "New Recommendation: $action",
                    "Dr. $doctorName has recommended $action for patient $patientName.",
                    route('reception.appointments.index'),
                    'info'
                ));
            }
        }

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('success', 'Diagnosis saved. Reception has been notified of the next steps.');
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
        // Allow Doctor OR Patient (if it's their own link)
        if ($diagnosis->doctor_id !== auth()->id()) {
            // Check if it is the Patient viewing their own
            // Note: $diagnosis->patient returns Patient Model. $diagnosis->patient->user_id is the Auth ID.
            if ($diagnosis->patient && $diagnosis->patient->user_id === auth()->id()) {
                // Allowed
            } else {
                abort(403);
            }
        }

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

    /**
     * Store Lab Request
     */
    public function storeLabRequest(Request $request, Appointment $appointment)
    {
        abort_if($appointment->doctor_id !== auth()->id(), 403);

        $request->validate([
            'test_name' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $appointment->labReports()->create([
            'patient_id' => $appointment->patient_id,
            'uploaded_by' => auth()->id(), // Requested by
            'title' => $request->test_name,
            'status' => 'requested',
            // file_path is null initially
        ]);

        return back()->with('success', 'Lab test requested.');
    }
}
