<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['patient','doctor','department'])
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

        $data['status'] = 'scheduled';

        Appointment::create($data);

        return redirect()
            ->route('reception.appointments.index')
            ->with('success','Appointment created successfully');
    }
}
