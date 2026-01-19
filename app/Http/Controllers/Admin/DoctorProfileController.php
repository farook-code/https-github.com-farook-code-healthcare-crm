<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\DoctorProfile;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    public function index()
    {
        $doctors = User::whereHas('role', fn ($q) =>
            $q->where('slug', 'doctor')
        )->with('doctorProfile')->get();

        return view('admin.doctors.index', compact('doctors'));
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        $profile = $user->doctorProfile;

        return view('admin.doctors.profile', compact('user', 'departments', 'profile'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'specialization' => 'required|string',
            'qualification' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'consultation_fee' => 'nullable|integer',
        ]);

        DoctorProfile::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor profile updated');
    }
}
