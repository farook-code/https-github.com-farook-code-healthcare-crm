<?php
namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('patient_code', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('reception.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('reception.patients.create');
    }
    
    public function edit(Patient $patient)
    {
        return view('reception.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
         $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,'.$patient->user_id,
            'phone'     => 'nullable|string|max:20',
            'gender'    => 'required|in:male,female,other',
            'dob'       => 'required|date',
            'address'   => 'nullable|string',
            'insurance_provider' => 'nullable|string',
            'policy_number' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'allergies' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
        ]);

        $patient->update($request->except(['email', 'name'])); // Specific update for Patient table fields?
        // Actually Patient has 'name' and 'email' columns too (redundant but existing).
        $patient->update($request->all());

        // Sync with User model
        if($patient->user) {
            $patient->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('reception.patients.index')
            ->with('success', 'Patient details updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'gender'    => 'required|in:male,female,other',
            'dob'       => 'required|date',
            'address'   => 'nullable|string',
            'insurance_provider' => 'nullable|string',
            'policy_number' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ]);

        $role = \App\Models\Role::where('slug', 'patient')->firstOrFail();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt('password'), // Default password
            'role_id'  => $role->id,
            'status'   => true,
        ]);

        Patient::create([
            'user_id'      => $user->id,
            'patient_code' => 'P-' . date('Ymd') . '-' . rand(100, 999),
            'name'         => $request->name,
            'gender'       => $request->gender,
            'dob'          => $request->dob,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'blood_group'  => $request->blood_group,
            'insurance_provider' => $request->insurance_provider,
            'policy_number' => $request->policy_number,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'allergies' => $request->allergies,
            'current_medications' => $request->current_medications,
            'chronic_conditions' => $request->chronic_conditions,
            'status'       => true,
        ]);

        return redirect()->route('reception.appointments.create')
            ->with('success', 'Patient registered successfully. You can now book an appointment.');
    }

}
