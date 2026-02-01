<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabReport;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FhirController extends Controller
{
    /**
     * Handle incoming FHIR DiagnosticReport resources (Simulated Interoperability)
     */
    public function storeDiagnosticReport(Request $request)
    {
        // Simple FHIR-like endpoint
        $data = $request->validate([
            'resourceType' => 'required|in:DiagnosticReport',
            'status' => 'required',
            'subject.reference' => 'required', // e.g. "Patient/1"
            'presentedForm' => 'nullable|array'
        ]);

        // 1. Resolve Patient
        // Assumes reference format "Patient/{id}"
        $patientIdRef = $data['subject']['reference'];
        $parts = explode('/', $patientIdRef);
        $patientId = end($parts);
        
        $patient = Patient::find($patientId);
        
        // If patient not found by ID, maybe search by name if provided in display? 
        // For strict FHIR, ID reference is standard.
        if (!$patient) {
            return response()->json([
                'resourceType' => 'OperationOutcome', 
                'issue' => [[
                    'severity' => 'error', 
                    'code' => 'not-found', 
                    'diagnostics' => 'Patient with ID ' . $patientId . ' not found'
                ]]
            ], 404);
        }

        // 2. Handle File (Base64)
        $filePath = null;
        $fileType = 'application/pdf'; // Default
        
        if (!empty($data['presentedForm'][0]['data'])) {
            $base64Data = $data['presentedForm'][0]['data'];
            $fileData = base64_decode($base64Data);
            
            // Determine extension from content type if possible, default to pdf
            $contentType = $data['presentedForm'][0]['contentType'] ?? 'application/pdf';
            $ext = ($contentType === 'image/jpeg') ? 'jpg' : 'pdf';
            $fileType = $contentType;

            $fileName = 'lab-reports/' . Str::random(40) . "." . $ext;
            
            Storage::disk('public')->put($fileName, $fileData);
            $filePath = $fileName;
        }

        // 3. Create Report
        // Using existing table structure.
        // We set uploaded_by to null (requires modifying migration if not nullable, 
        // or we pick the first admin user as 'System')
        $systemUser = \App\Models\User::whereHas('role', function($q){ $q->where('slug','admin'); })->first();

        $report = LabReport::create([
            'patient_id' => $patient->id,
            'appointment_id' => null, // Not linked to a specific appointment internally
            'uploaded_by' => $systemUser ? $systemUser->id : 1,
            'title' => 'External Lab Results (FHIR Import)',
            'file_path' => $filePath,
            'file_type' => $fileType,
        ]);

        return response()->json([
            'resourceType' => 'DiagnosticReport',
            'id' => (string) $report->id,
            'status' => 'final',
            'text' => [
                'status' => 'generated',
                'div' => '<div xmlns="http://www.w3.org/1999/xhtml">Report imported successfully.</div>'
            ]
        ], 201);
    }
}
