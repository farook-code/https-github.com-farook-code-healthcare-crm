<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\InsuranceClaim;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InsuranceClaimController extends Controller
{
    public function index()
    {
        $claims = InsuranceClaim::with(['patient', 'invoice'])
            ->latest()
            ->paginate(15);
            
        return view('reception.insurance.index', compact('claims'));
    }

    public function create(Invoice $invoice)
    {
        // Ensure invoice doesn't already have a claim
        if($invoice->insuranceClaim) {
            return redirect()->route('reception.insurance.show', $invoice->insuranceClaim)
                ->with('info', 'A claim already exists for this invoice.');
        }

        return view('reception.insurance.create', compact('invoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id|unique:insurance_claims,invoice_id',
            'provider_name' => 'required|string',
            'claim_number' => 'required|string|unique:insurance_claims,claim_number',
            'amount_claimed' => 'required|numeric|min:0',
            'submitted_at' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        
        $claim = InsuranceClaim::create([
            'invoice_id' => $invoice->id,
            'patient_id' => $invoice->patient_id,
            'provider_name' => $request->provider_name,
            'claim_number' => $request->claim_number,
            'amount_claimed' => $request->amount_claimed,
            'submitted_at' => $request->submitted_at,
            'notes' => $request->notes,
            'status' => 'pending'
        ]);

        return redirect()->route('reception.insurance.index')
            ->with('success', 'Insurance claim submitted successfully.');
    }

    public function show(InsuranceClaim $claim)
    {
        return view('reception.insurance.show', compact('claim'));
    }

    public function update(Request $request, InsuranceClaim $claim)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,paid',
            'amount_approved' => 'nullable|numeric',
            'rejection_reason' => 'nullable|required_if:status,rejected|string',
            'responded_at' => 'nullable|required_if:status,approved,rejected,paid|date',
        ]);

        $claim->update($request->all());

        // If paid via insurance, mark invoice as paid? 
        // Logic: if status is 'paid' (by insurance to clinic), we update invoice.
        if ($request->status === 'paid' && $claim->invoice->status !== 'paid') {
            $claim->invoice->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', 'Claim updated.');
    }
}
