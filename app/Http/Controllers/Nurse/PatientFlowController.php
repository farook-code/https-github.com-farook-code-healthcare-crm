<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientFlowController extends Controller
{
    public function index()
    {
        $columns = [
            'scheduled'       => 'Check In Pending',
            'checked_in'      => 'Vitals Pending',
            'vitals_done'     => 'Waiting for Doctor',
            'in_consultation' => 'With Doctor',
            'pharmacy'        => 'Pharmacy / Billing',
            'completed'       => 'Completed'
        ];

        // Fetch Today's Appointments
        $appointments = Appointment::with(['patient', 'doctor', 'department'])
            ->whereDate('appointment_date', Carbon::today())
            ->whereIn('status', array_keys($columns))
            ->get();

        return view('nurse.flow.index', compact('appointments', 'columns'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:appointments,id',
            'status' => 'required|string'
        ]);

        $appointment = Appointment::find($request->id);
        $appointment->status = $request->status;
        $appointment->save();

        return response()->json(['success' => true]);
    }
}
