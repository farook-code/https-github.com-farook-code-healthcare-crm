<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class TelemedicineController extends Controller
{
    public function join(Appointment $appointment)
    {
        // Security Check: Only allow doctor or patient linked to this appointment
        $user = auth()->user();
        if ($user->id !== $appointment->doctor_id && $user->id !== $appointment->patient->user_id) {
            abort(403, 'Unauthorized access to this consultation room.');
        }

        // Room Name Generator (Unique & Hard to guess)
        $roomName = 'HealthFlow_Consult_' . md5($appointment->id . $appointment->created_at);

        $userName = $user->name;
        $isDoctor = $user->hasRole('doctor');

        return view('telemedicine.room', compact('roomName', 'userName', 'isDoctor', 'appointment'));
    }
}
