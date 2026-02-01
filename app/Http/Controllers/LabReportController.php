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
    public function index(?User $patient = null)
    {
         $user = auth()->user();
         $role = $user->role->slug;
         
         // If specific patient records requested
         if ($patient) {
             // Patients can only view their own records
             if ($role === 'patient' && $user->id !== $patient->id) {
                 abort(403, 'Unauthorized');
             }
             
             // Staff check
             if (!in_array($role, ['admin', 'doctor', 'nurse', 'reception', 'lab_technician', 'patient'])) {
                 abort(403, 'Unauthorized Role');
             }

             // $patient is the User. Need Patient Profile.
             $profile = $patient->patient; // Assuming relationship exists
             
             if (!$profile) {
                 // Try to look up manually if relation not loaded or issues
                 $profile = \App\Models\Patient::where('user_id', $patient->id)->first();
             }
             
             $reports = $profile ? LabReport::where('patient_id', $profile->id)->latest()->get() : collect([]);
             
             return view('lab-reports.index', compact('patient', 'reports', 'profile'));
         }

         // Generic / Staff View (All Reports)
         if (!in_array($role, ['admin', 'doctor', 'nurse', 'reception', 'lab_technician'])) {
             // If patient tries to access generic route, redirect to their own records
             if ($role === 'patient') {
                 return redirect()->route('lab-reports.patient-records', $user->id);
             }
             abort(403, 'Unauthorized');
         }

         // Fetch all reports with relationships
         $query = LabReport::with(['patient.user', 'uploader']);

         // Optional: Filter by Search
         if (request('search')) {
             $term = request('search');
             $query->where(function($q) use ($term) {
                 $q->where('title', 'like', "%{$term}%")
                   ->orWhereHas('patient', function($p) use ($term) {
                       $p->where('name', 'like', "%{$term}%");
                   });
             });
         }

         // Optional: Filter by Date (Checking Old files)
         if (request('date_from')) {
             $query->whereDate('created_at', '>=', request('date_from'));
         }
         if (request('date_to')) {
             $query->whereDate('created_at', '<=', request('date_to'));
         }

         $reports = $query->latest()->paginate(20)->withQueryString();
         
         // Use a general view (reuse index but handle missing patient var, or create new)
         // Let's create a new view 'lab-reports.all' 
         return view('lab-reports.all', compact('reports'));
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

        // Resolve Patient Profile from Appointment (which holds User ID)
        $patientUser = User::find($appointment->patient_id);
        $patientProfile = $patientUser ? $patientUser->patient : null;

        if (!$patientProfile) {
             return back()->with('error', 'Cannot upload report: Patient profile missing.');
        }

        // Optimized Storage Path: lab-reports/YYYY/MM/patient_id
        $folder = 'lab-reports/' . date('Y/m') . '/' . $patientProfile->id;
        $path = $file->store($folder);

        $report = LabReport::create([
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

        // ✅ Notify User (Bell Icon)
        $patientUser->notify(new \App\Notifications\LabReportReady($report, auth()->user()));

        // Notify Doctor if exists for appointment
        if ($appointment->doctor && $appointment->doctor->user) {
             $appointment->doctor->user->notify(new \App\Notifications\LabReportReady($report, auth()->user())); 
        }

        return back()->with('success', 'Lab report uploaded and patient notified via chat.');
    }

    public function update(Request $request, LabReport $labReport)
    {
        $request->validate([
            'report_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('report_file');
        $folder = 'lab-reports/' . date('Y/m') . '/' . $labReport->patient_id;
        $path = $file->store($folder); 

        $labReport->update([
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        // Resolve Patient User for notification
        $patientUser = $labReport->patient ? $labReport->patient->user : null;

        if ($patientUser) {
             // ✅ Auto-Message to Patient
            \App\Services\ChatSystemMessage::send(
                auth()->id(), 
                $patientUser->id, 
                "Your Lab Report '{$labReport->title}' is now ready.",
                '/storage/' . $path,
                $file->getClientOriginalExtension()
            );

            // ✅ Notify User
            $patientUser->notify(new \App\Notifications\LabReportReady($labReport, auth()->user()));
        }

        return back()->with('success', 'Lab report uploaded successfully.');
    }

    public function storeDirect(Request $request) 
    {
         $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'title' => 'required|string',
            'report_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $profile = Patient::find($request->patient_id);
        
        // Find latest appointment for context, or leave null
        $latestAppointment = Appointment::where('patient_id', $profile->id)->latest()->first();
        
        $file = $request->file('report_file');
        $folder = 'lab-reports/' . date('Y/m') . '/' . $profile->id;
        $path = $file->store($folder);

        $report = LabReport::create([
            'appointment_id' => $latestAppointment ? $latestAppointment->id : null, // Nullable if schema supports, else use latest
            'patient_id'     => $profile->id,
            'uploaded_by'    => auth()->id(),
            'title'          => $request->title,
            'file_path'      => $path,
            'file_type'      => $file->getClientOriginalExtension(),
        ]);

        // Notifications
        if ($profile->user) {
            \App\Services\ChatSystemMessage::send(
                auth()->id(), 
                $profile->user->id, 
                "A new Lab Report '{$request->title}' has been uploaded.",
                '/storage/' . $path,
                $file->getClientOriginalExtension()
            );
            $profile->user->notify(new \App\Notifications\LabReportReady($report, auth()->user()));
        }

        return redirect()->route('lab-reports.index')->with('success', 'Report uploaded successfully via Quick Scan.');
    }

    public function download(LabReport $labReport)
    {
        // Authorization check
        $user = auth()->user();
        if ($user->role?->slug === 'patient' && $user->id !== $labReport->patient->user_id) {
             abort(403);
        }
        
        return Storage::download($labReport->file_path, $labReport->title . '.' . $labReport->file_type);
    }
    /**
     * AJAX endpoint to find patient from barcode
     */
    public function scanLookup(Request $request)
    {
        $code = $request->term;
        
        // Try Patient ID (PAT-1002 or just 1002)
        $id = preg_replace('/[^0-9]/', '', $code);
        
        $patient = \App\Models\Patient::where('id', $id)
            ->orWhere('national_id', $code)
            ->first();

        if ($patient) {
            return response()->json([
                'success' => true,
                'patient' => [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'user_id' => $patient->user_id
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Patient not found']);
    }

    public function quickUploadScan() {
        return view('lab-reports.scan-upload');
    }
}
