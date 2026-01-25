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
        if ($prescription->diagnosis->patient->user_id !== auth()->id()) {
            abort(403);
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
