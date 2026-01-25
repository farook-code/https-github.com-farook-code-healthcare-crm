<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Waitlist;
use App\Models\Patient;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    public function index()
    {
        $waitlist = Waitlist::with(['patient', 'department', 'doctor'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        $patients = Patient::select('id', 'first_name', 'last_name')->get(); // Simple fetch for dropdown
        $departments = Department::all();
        $doctors = User::whereHas('role', function($q){ $q->where('slug', 'doctor'); })->get();

        return view('reception.waitlist.index', compact('waitlist', 'patients', 'departments', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'department_id' => 'nullable|exists:departments,id',
            'doctor_id' => 'nullable|exists:users,id',
            'preferred_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        Waitlist::create($request->all());

        return redirect()->back()->with('success', 'Patient added to waitlist.');
    }

    public function update(Request $request, Waitlist $waitlist)
    {
        $request->validate([
            'status' => 'required|in:pending,booked,cancelled'
        ]);

        $waitlist->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Waitlist status updated.');
    }
    
    public function destroy(Waitlist $waitlist)
    {
        $waitlist->delete(); // Or soft delete if model utilized it, but hard delete is fine for now
        return redirect()->back()->with('success', 'Removed from waitlist.');
    }
}
