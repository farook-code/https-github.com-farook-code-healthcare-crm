<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IpdAdmission;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class IpdAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'admitted'); // admitted, discharged, all

        $query = IpdAdmission::with(['patient', 'doctor', 'bed.ward']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $admissions = $query->latest('admission_date')->paginate(10);
        
        $stats = [
            'total_admitted' => IpdAdmission::where('status', 'admitted')->count(),
            'available_beds' => Bed::where('is_available', true)->count(),
            'occupied_beds' => Bed::where('is_available', false)->count(),
        ];

        return view('admin.ipd.admissions.index', compact('admissions', 'stats', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::select('id', 'name')->get();
        $doctors = User::whereHas('role', function($q) {
            $q->where('slug', 'doctor');
        })->select('id', 'name')->get();
        // Get available beds grouped by Ward
        $wards = Ward::with(['beds' => function($q) {
            $q->where('is_available', true)->where('status', 'available');
        }])->get();

        return view('admin.ipd.admissions.create', compact('patients', 'doctors', 'wards'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_date' => 'required|date',
            'reason_for_admission' => 'required|string',
            'advance_payment' => 'nullable|numeric|min:0',
        ]);

        // Check availability strictly
        $bed = Bed::findOrFail($request->bed_id);
        if (!$bed->is_available) {
            return back()->with('error', 'Selected bed is no longer available.');
        }

        // Create Admission
        $admission = IpdAdmission::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'bed_id' => $request->bed_id,
            'admission_date' => $request->admission_date,
            'reason_for_admission' => $request->reason_for_admission,
            'advance_payment' => $request->advance_payment ?? 0,
            'status' => 'admitted'
        ]);

        // Mark Bed Occupied
        $bed->update(['is_available' => false, 'status' => 'occupied']);

        return redirect()->route('admin.ipd.admissions.index')->with('success', 'Patient admitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IpdAdmission $admission)
    {
        $admission->load(['patient', 'doctor', 'bed.ward']);
        return view('admin.ipd.admissions.show', compact('admission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Discharge a patient
     */
    public function discharge(Request $request, IpdAdmission $admission)
    {
        if ($admission->status !== 'admitted') {
            return back()->with('error', 'Patient is already discharged.');
        }

        $request->validate([
            'discharge_date' => 'required|date|after_or_equal:'.$admission->admission_date,
            'discharge_notes' => 'required|string',
            'total_estimate' => 'required|numeric|min:0',
        ]);

        // Update Admission
        $admission->update([
            'status' => 'discharged',
            'discharge_date' => $request->discharge_date,
            'discharge_notes' => $request->discharge_notes,
            'total_estimate' => $request->total_estimate,
        ]);

        // Free the Bed
        if ($admission->bed) {
            $admission->bed->update(['is_available' => true, 'status' => 'available']);
        }

        // Generate Final Invoice
        $invoice = \Illuminate\Support\Facades\DB::transaction(function () use ($admission, $request) {
            $invoice = \App\Models\Invoice::create([
                'patient_id' => $admission->patient_id,
                'ipd_admission_id' => $admission->id,
                'category' => 'ipd',
                'amount' => $request->total_estimate,
                'status' => 'unpaid',
                'issued_at' => now(),
            ]);

            \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => 'In-Patient Bed Charges & Services',
                'quantity' => 1,
                'unit_price' => $request->total_estimate,
                'total_price' => $request->total_estimate
            ]);

            return $invoice;
        });

        return redirect()->route('reception.invoices.print', $invoice->id)->with('success', 'Patient discharged and Invoice generated.');
    }
}
