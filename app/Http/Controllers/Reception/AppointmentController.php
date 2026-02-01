<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient','doctor','department','invoice'])
            ->latest()
            ->get();

        return view('reception.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = User::whereHas('role', fn ($q) =>
            $q->where('slug','patient')
        )->get();

        $doctors = User::whereHas('role', fn ($q) =>
            $q->where('slug','doctor')
        )->get();

        $departments = Department::all();

        return view(
            'reception.appointments.create',
            compact('patients','doctors','departments')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        // Conflict Detection
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('appointment_time', $request->appointment_time)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withErrors(['doctor_id' => 'This doctor is already booked at this time.'])->withInput();
        }

        // Check Doctor Working Hours
        $schedule = \App\Models\DoctorSchedule::where('doctor_id', $request->doctor_id)->first();
        if ($schedule) {
            $dayOfWeek = strtolower(\Carbon\Carbon::parse($request->appointment_date)->format('l')); // monday, tuesday...
            $hours = $schedule->working_hours[$dayOfWeek] ?? null;

            if (!$hours) { // If null, doctor is OFF
                 return back()->withErrors(['appointment_date' => 'Doctor is not available on ' . ucfirst($dayOfWeek) . 's.'])->withInput();
            }
            
            // Simple check: is time within start and end?
            // array: ['09:00', '17:00']
            $apptTime = $request->appointment_time;
            if ($apptTime < $hours[0] || $apptTime > $hours[1]) {
                 return back()->withErrors(['appointment_time' => "Doctor only works between {$hours[0]} and {$hours[1]} on " . ucfirst($dayOfWeek) . "s."])->withInput();
            }
        }


        $data['status'] = 'scheduled';

        $appointment = Appointment::create($data);

        // Load relationships for email
        $appointment->load(['patient', 'doctor', 'department']);

        // Send Notification
        if ($appointment->patient && $appointment->patient->email) {
             $user = \App\Models\User::find($appointment->patient_id);
             if ($user) {
                 $user->notify(new \App\Notifications\AppointmentBooked($appointment));
                 
                 // ✅ Auto-Message to Patient (Chat)
                 \App\Services\ChatSystemMessage::send(
                    auth()->id(), // Receptionist
                    $user->id, // Patient
                    "Your appointment has been confirmed with Dr. {$appointment->doctor->name} on {$appointment->appointment_date->format('M d, Y')} at {$appointment->appointment_time}."
                 );

                 // Send Email Confirmation (Queued)
                 try {
                     Mail::to($user->email)->queue(new AppointmentConfirmation($appointment));
                 } catch (\Exception $e) {
                     \Illuminate\Support\Facades\Log::error("Mail Error: " . $e->getMessage());
                 }

                 // Send WhatsApp Notification (Queued)
                 try {
                     if ($user->phone) {
                         \App\Jobs\SendWhatsAppMessage::dispatch($user->phone, 'hello_world', 'en_US');
                     }
                 } catch (\Exception $e) {
                     \Illuminate\Support\Facades\Log::error("WhatsApp Error: " . $e->getMessage());
                 }
             }
        }

        return redirect()
            ->route('reception.appointments.index')
            ->with('success','Appointment created and confirmation email sent.');
    }
    
    public function edit(Appointment $appointment)
    {
        $doctors = User::whereHas('role', fn ($q) => $q->where('slug','doctor'))->with('department')->get();
        $departments = Department::all();

        return view('reception.appointments.edit', compact('appointment', 'doctors', 'departments'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        // Conflict Detection (if changing time/doctor)
        if (
            $request->doctor_id != $appointment->doctor_id || 
            $request->appointment_date != $appointment->appointment_date->format('Y-m-d') ||
            $request->appointment_time != $appointment->appointment_time
        ) {
            $exists = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('appointment_time', $request->appointment_time)
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($exists) {
                return back()->withErrors(['doctor_id' => 'This doctor is already booked at this time.'])->withInput();
            }
        }

        $appointment->update($request->all());

        return redirect()->route('reception.appointments.index')
            ->with('success', 'Appointment updated successfully');
    }

    /**
     * Cancel an appointment (Shortcut)
     */
    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment cancelled.');
    }

    public function calendar()
    {
        return view('reception.appointments.calendar');
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');

        $query = Appointment::with(['patient', 'doctor']);

        if ($start && $end) {
            $query->whereBetween('appointment_date', [
                \Carbon\Carbon::parse($start)->toDateString(),
                \Carbon\Carbon::parse($end)->toDateString()
            ]);
        }

        $appointments = $query->get();

        $events = $appointments->map(function ($appt) {
            return [
                'id' => $appt->id,
                'title' => $appt->patient->name . ' (' . $appt->doctor->name . ')',
                'start' => $appt->appointment_date->format('Y-m-d') . 'T' . $appt->appointment_time,
                'backgroundColor' => match ($appt->status) {
                    'completed' => '#10b981', // green-500
                    'cancelled' => '#ef4444', // red-500
                    default => '#3b82f6',     // blue-500
                },
                'borderColor' => 'transparent',
                'extendedProps' => [
                     'status' => $appt->status
                ],
                'url' => route('reception.appointments.edit', $appt->id) // Allow clicking to edit
            ];
        });

        return response()->json($events);
    }

    /**
     * Handle Drag-and-Drop Reschedule
     */
    public function move(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:appointments,id',
            'start' => 'required', // FullCalendar sends ISO string
        ]);

        $appointment = Appointment::find($request->id);
        
        // FullCalendar sends timezone-aware strings, parsing carefully
        $newStart = \Carbon\Carbon::parse($request->start); 
        // Note: Assuming User's local time matches server time for simplicity or using UTC. 
        // Ideally, we'd handle timezones, but for local deployment, this works.
        
        $newDate = $newStart->format('Y-m-d');
        $newTime = $newStart->format('H:i'); // 24-hour format

        // Check conflicts at new time
        $conflict = Appointment::where('doctor_id', $appointment->doctor_id)
            ->where('appointment_date', $newDate)
            ->where('appointment_time', $newTime)
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'Doctor is already booked at this time.'], 409);
        }

        $appointment->update([
            'appointment_date' => $newDate,
            'appointment_time' => $newTime,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment rescheduled to ' . $newStart->format('M d, H:i')
        ]);
    }
}
