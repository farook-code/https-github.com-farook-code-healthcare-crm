<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtBooking;
use App\Models\OperationTheater;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class OtBookingController extends Controller
{
    public function index()
    {
        $bookings = OtBooking::with(['theater', 'patient', 'surgeon'])->latest('scheduled_start')->paginate(10);
        return view('admin.ot.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $theaters = OperationTheater::where('status', '!=', 'maintenance')->get();
        $patients = Patient::select('id', 'name')->get();
        $surgeons = User::role('doctor')->get();
        return view('admin.ot.bookings.create', compact('theaters', 'patients', 'surgeons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'operation_theater_id' => 'required',
            'patient_id' => 'required',
            'lead_surgeon_id' => 'required',
            'scheduled_start' => 'required|date',
            'scheduled_end' => 'required|date|after:scheduled_start',
            'procedure_name' => 'required',
        ]);

        OtBooking::create($request->all());

        // Update Theater Status to In Use if happening now? (Optional logic)

        return redirect()->route('admin.ot.bookings.index')->with('success', 'Surgery Scheduled.');
    }
}
