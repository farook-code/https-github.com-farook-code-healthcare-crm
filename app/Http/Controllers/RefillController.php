<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;

class RefillController extends Controller
{
    /**
     * Patient requests a refill
     */
    public function requestRefill(Prescription $prescription)
    {
        // 1. Authorization: Ensure patient owns this prescription (via Diagnosis -> Appointment -> Patient)
        // 1. Authorization: Ensure patient owns this prescription
        // We need to resolve the Patient User ID from the prescription's diagnosis
        $patientOwnerId = $prescription->diagnosis->patient->user_id ?? null;
        
        // Debug/Fallback: If relationship is broken or old data, try direct checking if patient_id matches auth id (legacy support)
        if (!$patientOwnerId) {
             // Fallback: maybe patient_id KEY is actually the user_id (old schema)?
             $patientOwnerId = $prescription->diagnosis->patient_id; 
        }

        if ($patientOwnerId !== auth()->id()) {
            abort(403, 'Unauthorized: This prescription does not belong to you.');
        }

        $prescription->update([
            'refill_requested' => true,
            'refill_requested_at' => now(),
            'refill_status' => 'requested'
        ]);

        return back()->with('success', 'Refill request sent to your doctor.');
    }

    /**
     * Doctor/Nurse views requests
     */
    public function indexRequests()
    {
        // For simple MVP: List all pending requests
        $requests = Prescription::with(['diagnosis.patient', 'diagnosis.doctor'])
            ->where('refill_status', 'requested')
            ->latest('refill_requested_at')
            ->get();

        return view('doctors.prescriptions.refills', compact('requests'));
    }

    /**
     * Doctor approves/denies
     */
    public function updateStatus(Request $request, Prescription $prescription)
    {
        $request->validate([
            'status' => 'required|in:approved,denied'
        ]);

        $prescription->update([
            'refill_status' => $request->status,
            'refill_requested' => false // Reset flag or keep history? Let's say false means "handled"
        ]);

        // Ideally notify patient here

        return back()->with('success', 'Refill request ' . $request->status);
    }
}
