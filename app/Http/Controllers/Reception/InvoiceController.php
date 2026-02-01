<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Appointment;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceipt;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices for reception.
     */
    public function index()
    {
        $query = Invoice::with(['appointment.patient', 'appointment.doctor', 'items']);

        // Filter by role
        if (auth()->check() && auth()->user()->role) {
            $roleSlug = auth()->user()->role->slug;
            if ($roleSlug === 'pharmacist') {
                $query->where('category', 'pharmacy');
            } elseif ($roleSlug === 'lab_technician') {
                $query->where('category', 'lab');
            }
        }

        $invoices = $query->latest()->paginate(15);

        return view('reception.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(Request $request)
    {
        // Get all appointments that don't have an invoice yet
        $appointments = Appointment::with(['patient', 'doctor.doctorProfile', 'department'])
            ->whereDoesntHave('invoice')
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get();

        // Active IPD Admissions
        $ipdAdmissions = \App\Models\IpdAdmission::where('status', 'admitted')
            ->with(['patient', 'bed.ward'])
            ->get();

        // All Patients (for Pharmacy/Lab)
        $patients = \App\Models\Patient::select('id', 'name', 'patient_code', 'phone')->latest()->get();

        // Pre-select appointment if passed via query string
        $selectedAppointment = null;
        if ($request->has('appointment_id')) {
            $selectedAppointment = Appointment::with(['patient', 'doctor.doctorProfile', 'department'])
                ->find($request->appointment_id);
        }

        // Get all medicines for the dropdown
        $medicines = \App\Models\Medicine::where('stock_quantity', '>', 0)->get();
        // Get all lab tests
        $labTests = \App\Models\LabTest::orderBy('name')->get();

        return view('reception.invoices.create', compact('appointments', 'selectedAppointment', 'medicines', 'ipdAdmissions', 'patients', 'labTests'));
    }

    /**
     * Get appointment details for AJAX (returns doctor's consultation fee).
     */
    public function getAppointmentDetails(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.doctorProfile', 'department', 'validDiagnosis.prescriptions']);
        
        $consultationFee = $appointment->doctor->doctorProfile->consultation_fee ?? 100;

        // Fetch prescriptions from the diagnosis linked to this appointment
        $medicines = [];
        if($appointment->validDiagnosis && $appointment->validDiagnosis->prescriptions) {
            foreach($appointment->validDiagnosis->prescriptions as $p) {
                // Try to match with Inventory
                $inv = \App\Models\Medicine::where('name', 'LIKE', $p->medicine_name)->first();
                $medicines[] = [
                    'name' => $p->medicine_name,
                    'price' => $inv ? $inv->price : 0,
                    'medicine_id' => $inv ? $inv->id : null,
                    'dosage' => $p->dosage
                ];
            }
        }
        
        return response()->json([
            'patient_name' => $appointment->patient->name ?? 'Unknown',
            'doctor_name' => $appointment->doctor->name ?? 'Unknown',
            'department_name' => $appointment->department->name ?? 'General',
            'consultation_fee' => $consultationFee,
            'appointment_date' => $appointment->appointment_date->format('M d, Y'),
            'appointment_time' => $appointment->appointment_time,
            'prescriptions' => $medicines
        ]);
    }

    /*
     * Fetch pending items (recent prescriptions) for a patient.
     */
    public function getPendingItems(\App\Models\Patient $patient)
    {
        // Get prescriptions from the last 7 days from any diagnosis
        $recentDiagnoses = \App\Models\Diagnosis::where('patient_id', $patient->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->with('prescriptions')
            ->latest()
            ->get();

        $medicines = [];
        foreach($recentDiagnoses as $d) {
            foreach($d->prescriptions as $p) {
                // Simple duplication check or just list all
                 $inv = \App\Models\Medicine::where('name', 'LIKE', $p->medicine_name)->first();
                 $medicines[] = [
                    'name' => $p->medicine_name,
                    'price' => $inv ? $inv->price : 0,
                    'medicine_id' => $inv ? $inv->id : null,
                    'dosage' => $p->dosage,
                    'date' => $d->created_at->format('M d')
                ];
            }
        }

        return response()->json([
            'prescriptions' => $medicines
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
             'patient_id' => 'nullable', // Derived if missing
             'category' => 'required|in:opd,ipd,pharmacy,lab',
             'types' => 'required|array',
             'prices' => 'required|array',
             'quantities' => 'required|array',
             'appointment_id' => 'nullable|required_if:category,opd',
             'ipd_admission_id' => 'nullable|required_if:category,ipd',
        ]);

        // Derive Patient ID
        $patientId = $request->patient_id;

        if (!$patientId && $request->appointment_id) {
            $appt = Appointment::find($request->appointment_id);
            if ($appt) $patientId = \App\Models\Patient::where('user_id', $appt->patient_id)->value('id');
        }

        if (!$patientId && $request->ipd_admission_id) {
             $adm = \App\Models\IpdAdmission::find($request->ipd_admission_id);
             if ($adm) $patientId = $adm->patient_id;
        }

        if (!$patientId) {
            return back()->with('error', 'Patient ID is required.');
        }

        $patient = \App\Models\Patient::find($patientId);
        if (!$patient) return back()->with('error', 'Invalid Patient.');

        // Prevent Duplicate Invoice for Appointment
        if ($request->category === 'opd' && $request->appointment_id) {
            $existing = Invoice::where('appointment_id', $request->appointment_id)->first();
            if ($existing) return back()->with('error', 'Invoice for this appointment already exists.');
        }

        // 1. Create Invoice Header
        $invoice = Invoice::create([
            'appointment_id' => $request->appointment_id,
            'ipd_admission_id' => $request->ipd_admission_id,
            'patient_id' => $patient->id,
            'category' => $request->category,
            'amount' => 0, 
            'status' => 'pending',
            'issued_at' => now(),
        ]);

        $grandTotal = 0;

        // 2. Loop through items
        foreach ($request->types as $index => $type) {
            $qty = $request->quantities[$index] ?? 1;
            $unitPrice = $request->prices[$index] ?? 0;
            $totalPrice = $unitPrice * $qty;
            $medId = $request->medicine_ids[$index] ?? null;
            
            $description = $request->descriptions[$index] ?? 'Service';

            \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $description,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'medicine_id' => $medId
            ]);

            $grandTotal += $totalPrice;
        }

        $invoice->update(['amount' => $grandTotal]);

        return redirect()->route('reception.invoices.index')->with('success', 'Invoice generated successfully.');
    }

    public function markAsPaid(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice is already paid.');
        }

        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => 'cash', 
        ]);

        $this->deductStock($invoice);

        return redirect()->route('reception.invoices.print', $invoice)
            ->with('success', 'Invoice marked as Paid. Ready to print.');
    }

    public function checkout(Invoice $invoice)
    {
        return view('reception.invoices.checkout', compact('invoice'));
    }

    public function processPayment(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('reception.appointments.index')->with('error', 'Invoice already paid.');
        }

        // Mock Payment Gateway processing
        $request->validate([
            'card_holder' => 'required|string',
            'card_number' => 'required',
            'expiry'      => 'required',
            'cvc'         => 'required',
        ]);

        $data = [
            'status'         => 'paid',
            'paid_at'        => now(),
            'payment_method' => 'stripe_credit_card',
        ];

        if (\Illuminate\Support\Facades\Schema::hasColumn('invoices', 'transaction_id')) {
            $data['transaction_id'] = 'ch_' . Str::random(24);
        }

        $invoice->update($data);
        $this->deductStock($invoice);

        // Send Payment Receipt
        try {
            if ($invoice->patient && $invoice->patient->user && $invoice->patient->user->email) {
                Mail::to($invoice->patient->user->email)->send(new PaymentReceipt($invoice));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Mail Error: " . $e->getMessage());
        }

        return redirect()->route('reception.invoices.print', $invoice)
            ->with('success', 'Payment processed. Ready to print.');
    }

    /**
     * Deduct stock for medicines in the invoice.
     */
    private function deductStock(Invoice $invoice)
    {
        foreach ($invoice->items as $item) {
            if ($item->medicine_id) {
                $medicine = \App\Models\Medicine::find($item->medicine_id);
                if ($medicine) {
                    // Decrement
                    $newStock = $medicine->stock_quantity - $item->quantity;
                    $medicine->update(['stock_quantity' => $newStock]);
                    
                    // Low Stock Alert
                    if ($newStock <= 10) {
                         // Find an Admin
                        $admin = \App\Models\User::whereHas('role', function($q){ 
                            $q->where('slug', 'admin'); 
                        })->first();

                        if ($admin) {
                            $admin->notify(new \App\Notifications\LowStockAlert($medicine));
                        }
                    }
                }
            }
        }
    }

    public function print(Invoice $invoice)
    {
        $invoice->load([
            'patient.user', 
            'appointment.doctor', 
            'appointment.department',
            'items.medicine'
        ]);
        
        return view('reception.invoices.print', compact('invoice'));
    }
}
