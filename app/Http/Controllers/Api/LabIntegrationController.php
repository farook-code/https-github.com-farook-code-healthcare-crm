<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabReport;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class LabIntegrationController extends Controller
{
    /**
     * Receive Lab Results via API (Mock HL7/FHIR endpoint)
     */
    public function receive(Request $request)
    {
        // Simple validation
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_name' => 'required|string',
            'results' => 'required', // Array or String
        ]);

        // Find system user or first admin to attribute this to
        $systemUser = User::role('admin')->first() ?? User::first();

        $report = LabReport::create([
            'patient_id' => $request->patient_id,
            'appointment_id' => $request->appointment_id ?? null, // Can be null if spontaneous lab
            'uploaded_by' => $systemUser->id,
            'title' => $request->test_name . ' (External Lab)',
            'result_data' => is_array($request->results) ? json_encode($request->results) : $request->results,
            'file_type' => 'data', // Structured data
            'status' => 'final',
            'external_id' => $request->external_id ?? 'LAB-' . time(),
        ]);

        return response()->json([
            'message' => 'Lab results received successfully',
            'report_id' => $report->id
        ], 201);
    }
}
