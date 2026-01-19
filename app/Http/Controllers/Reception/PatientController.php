<?php
namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function store(Request $request)
{
    // 1. Validate input
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'name'    => 'required|string',
        'gender'  => 'required|in:male,female,other',
    ]);

    // 2. Prevent duplicate patient record
    if (Patient::where('user_id', $request->user_id)->exists()) {
        return back()->withErrors([
            'user_id' => 'Patient profile already exists for this user.'
        ]);
    }

    // 3. Create patient
    Patient::create([
        'user_id'      => $request->user_id,
        'patient_code' => 'PAT-' . strtoupper(\Illuminate\Support\Str::random(6)),
        'name'         => $request->name,
        'gender'       => $request->gender,
        'status'       => true,
    ]);

    return back()->with('success', 'Patient created successfully');
}

}
