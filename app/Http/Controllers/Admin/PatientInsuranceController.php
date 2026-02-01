<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientInsurance;
use Illuminate\Http\Request;

class PatientInsuranceController extends Controller
{
    public function index()
    {
        $policies = PatientInsurance::with(['patient', 'provider'])->latest()->paginate(15);
        return view('admin.insurance.policies.index', compact('policies'));
    }
}
