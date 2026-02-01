<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function create()
    {
        $doctors = User::whereHas('role', fn($q) => $q->where('slug', 'doctor'))
            ->with(['doctorProfile'])
            ->get();
            
        return view('patient.appointments.create', compact('doctors'));
    }

    /**
     * AJAX: Get Available Time Slots
     */
    public function getSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'date' => 'required|date'
        ]);

        $doctor = User::find($request->doctor_id);
        $date = $request->date;
        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));

        // 1. Get Profile/Schedule
        $schedule = \App\Models\DoctorSchedule::where('doctor_id', $doctor->id)->first();
        
        // Default 9-5 if no schedule found? Or assume closed? 
        // Let's assume standard 09:00 - 17:00 if no schedule entry, just to be safe, or return empty.
        // Based on previous code, we seeded everyone.
        
        $start = "09:00";
        $end = "17:00";
        
        if ($schedule && isset($schedule->working_hours[$dayOfWeek])) {
            $hours = $schedule->working_hours[$dayOfWeek];
            if (!$hours) {
                return response()->json(['slots' => []]); // Doctor is OFF
            }
            $start = $hours[0];
            $end = $hours[1];
        } elseif ($schedule && !isset($schedule->working_hours[$dayOfWeek])) {
             return response()->json(['slots' => []]); // Off (explicitly null in structure)
        }

        // 2. Generate All possible 30-min slots
        $startTime = \Carbon\Carbon::parse($date . ' ' . $start);
        $endTime = \Carbon\Carbon::parse($date . ' ' . $end);
        
        $allSlots = [];
        while ($startTime < $endTime) {
            $allSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }

        // 3. Get Booked Slots
        $bookedSlots = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->map(function($time) {
                return \Carbon\Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // 4. Diff
        $availableSlots = array_values(array_diff($allSlots, $bookedSlots));

        return response()->json(['slots' => $availableSlots]);
    }

    public function store(Request $request)
    {
        $patient = auth()->user()->patient;
        abort_if(!$patient, 403, 'Patient profile not found');

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:500'
        ]);

        // 1. Check Conflict
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withErrors(['appointment_time' => 'This time slot is already taken. Please choose another.'])->withInput();
        }

        // 2. Check Doctor Working Hours Logic (Duplicate of getSlots logic but for validation safety)
        $schedule = \App\Models\DoctorSchedule::where('doctor_id', $request->doctor_id)->first();
        if ($schedule) {
            $dayOfWeek = strtolower(\Carbon\Carbon::parse($request->appointment_date)->format('l')); // monday, tuesday...
            $hours = $schedule->working_hours[$dayOfWeek] ?? null;

            if (!$hours) { // If null, doctor is OFF
                 return back()->withErrors(['appointment_date' => 'Doctor is not available on ' . ucfirst($dayOfWeek) . 's.'])->withInput();
            }
            
            // Simple check: is time within start and end?
            $apptTime = $request->appointment_time;
            if ($apptTime < $hours[0] || $apptTime > $hours[1]) {
                 return back()->withErrors(['appointment_time' => "Doctor only works between {$hours[0]} and {$hours[1]} on " . ucfirst($dayOfWeek) . "s."])->withInput();
            }
        }

        $doctor = User::find($request->doctor_id);
        $departmentId = $doctor->doctorProfile->department_id 
            ?? \App\Models\Department::first()->id; // Fallback

        Appointment::create([
            'patient_id' => auth()->id(), // Schema expects User ID
            'doctor_id' => $request->doctor_id,
            'department_id' => $departmentId,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'scheduled', 
            'type' => 'in-person'
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment booked successfully.');
    }
}
