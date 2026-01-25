<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    /**
     * Kiosk Screen (No Auth required technically, but we protect it for now)
     */
    public function index()
    {
        return view('reception.kiosk.index');
    }

    /**
     * Process Check-in via ID
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
        ]);

        $patient = Patient::find($request->patient_id);

        // Find today's appointment
        $appointment = Appointment::where('patient_id', $patient->id)
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['confirmed', 'scheduled'])
            ->first();

        if (!$appointment) {
            return response()->json([
                'success' => false,
                'message' => 'No scheduled appointment found for today.'
            ]);
        }

        // Update status
        $appointment->update(['status' => 'arrived']); // or 'in_progress' or 'waiting'

        // Add to flow board? (Optional)

        return response()->json([
            'success' => true,
            'message' => "Welcome, {$patient->name}! You are now checked in.",
            'doctor' => $appointment->doctor->name
        ]);
    }
}
