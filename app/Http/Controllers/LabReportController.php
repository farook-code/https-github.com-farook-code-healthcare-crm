<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\LabReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabReportController extends Controller
{
    public function create(Appointment $appointment)
    {
        return view('lab-reports.create', compact('appointment'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $request->validate([
            'title' => 'required|string',
            'report_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('report_file');
        $path = $file->store('lab-reports', 'public');

        LabReport::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $appointment->patient_id,
            'uploaded_by'    => auth()->id(),
            'title'          => $request->title,
            'file_path'      => $path,
            'file_type'      => $file->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'Lab report uploaded successfully');
    }
}
