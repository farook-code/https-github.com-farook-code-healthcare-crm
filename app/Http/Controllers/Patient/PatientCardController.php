<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientCardController extends Controller
{
    public function show()
    {
        $patient = auth()->user()->patientProfile;
        
        if (!$patient) {
            return back()->with('error', 'Patient profile not complete.');
        }

        return view('patient.card', compact('patient'));
    }
}
