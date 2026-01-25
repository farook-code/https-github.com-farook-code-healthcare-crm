<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\LabReport;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class LabReportController extends Controller
{
    public function index(User $patient)
    {
         $user = auth()->user();
         $role = $user->role->slug;
         
         // Patients can only view their own records
         if ($role === 'patient' && $user->id !== $patient->id) {
             abort(403, 'Unauthorized');
         }
         
         // Staff (Admin, Doctor, Nurse, Reception) can view all
         if (!in_array($role, ['admin', 'doctor', 'nurse', 'reception', 'patient'])) {
             abort(403, 'Unauthorized Role');
         }

         // $patient is the User. We need the Patient Profile ID for the query.
         $profile = $patient->patient; 
         
         if (!$profile) {
             // Handle case where User has no Patient profile yet
             return back()->with('error', 'Patient profile not found for this user.');
         }

         $reports = LabReport::where('patient_id', $profile->id)->latest()->get();
         
         // We pass 'patient' as the User object to the view (usually view expects name etc which User has)
         // But let's check view. If view uses $patient->dob, we need profile.
         // View usually shows "Medical Records for [Name]".
         return view('lab-reports.index', compact('patient', 'reports', 'profile'));
    }

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
        $path = $file->store('lab-reports'); 

        // Resolve Patient Profile from Appointment (which holds User ID)
        $patientUser = User::find($appointment->patient_id);
        $patientProfile = $patientUser ? $patientUser->patient : null;

        if (!$patientProfile) {
             return back()->with('error', 'Cannot upload report: Patient profile missing.');
        }

        LabReport::create([
            'appointment_id' => $appointment->id,
            'patient_id'     => $patientProfile->id, // Correct Profile ID
            'uploaded_by'    => auth()->id(),
            'title'          => $request->title,
            'file_path'      => $path,
            'file_type'      => $file->getClientOriginalExtension(),
        ]);

        // ✅ Auto-Message to Patient
        \App\Services\ChatSystemMessage::send(
            auth()->id(), // Sender: Staff/Doctor who uploaded
            $patientUser->id, // Receiver: Patient
            "A new Lab Report '{$request->title}' has been uploaded to your medical records.",
            '/storage/' . $path,
            $file->getClientOriginalExtension()
        );

        return back()->with('success', 'Lab report uploaded and patient notified via chat.');
    }

    public function download(LabReport $labReport)
    {
        // Authorization check (simplified for now, ideally use Policies)
        $user = auth()->user();
        if ($user->hasRole('patient') && $user->id !== $labReport->patient->user_id) {
             abort(403);
        }
        
        return Storage::download($labReport->file_path, $labReport->title . '.' . $labReport->file_type);
    }
}
