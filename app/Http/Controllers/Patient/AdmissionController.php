<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        abort_if(!$patient, 403, 'Patient profile not found');

        $admissions = \App\Models\IpdAdmission::where('patient_id', $patient->id)
            ->with(['bed.ward', 'doctor'])
            ->latest('admission_date')
            ->paginate(10);

        return view('patient.admissions.index', compact('admissions'));
    }
}
