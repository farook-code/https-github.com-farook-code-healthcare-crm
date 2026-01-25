<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Appointment;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function store(Request $request, Appointment $appointment)
    {
        // If request has 'amount', it's the old simple form (legacy support or fallback)
        if ($request->has('amount') && !$request->has('items')) {
            // ... legacy logic ...
            Invoice::create([
                'appointment_id' => $appointment->id,
                'patient_id' => \App\Models\Patient::where('user_id', $appointment->patient_id)->value('id'),
                'amount' => $request->amount,
                'status' => 'pending',
                'issued_at' => now(),
            ]);
            return back()->with('success', 'Simple Invoice generated.');
        }

        // New Itemized Logic
        $request->validate([
             'types' => 'required|array',
             'prices' => 'required|array',
             'quantities' => 'required|array',
        ]);

        if ($appointment->invoice) {
            return back()->with('error', 'Invoice already exists.');
        }

        $patientProfile = \App\Models\Patient::where('user_id', $appointment->patient_id)->first();
        if (!$patientProfile) return back()->with('error', 'Patient profile missing.');

        // 1. Create Invoice Header (Total 0 initially)
        $invoice = Invoice::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $patientProfile->id,
            'amount' => 0, // Will calculate below
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
            
            // Description logic
            $description = '';
            if ($type === 'medicine') {
                // If medicine, the description is the Medicine Name
                $med = \App\Models\Medicine::find($medId);
                $description = $med ? $med->name . ' (' . $med->strength . ')' : 'Unknown Medicine';
            } else {
                // If service, fetch from the items['service'] array, but array index logic is tricky because services might not align with 'types' index if keys are sparse.
                // However, the JS submits all arrays with same length (hidden inputs included).
                // Wait. My JS used name="items[service][]" which results in a separate array only containing services.
                // That's tricky to map by index. 
                
                // FIX: Let's assume description is passed in a unified array 'descriptions' or I fix the JS logic.
                // Actually, let's fix the PHP logic to just read 'items.service' array carefully? No, indexes won't match.
                
                // RETHINK: The easiest way is to use a single array `descriptions[]` in the form.
                // For medicine, the JS populates `descriptions[]` with the selected medicine name name.
                // For service, the user types it.
                // Let's assume I fix JS to put everything in `descriptions[]`.
                
                // NOTE: I will update the View JS in a moment to ensure `descriptions[]` is sent for ALL rows.
                $description = $request->descriptions[$index] ?? 'Service';
            }

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

        return back()->with('success', 'Invoice generated successfully with ' . count($request->types) . ' items.');
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

        return back()->with('success', 'Invoice marked as Paid and Stock updated.');
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

        return redirect()->route('reception.appointments.index')
            ->with('success', 'Payment processed and Stock updated.');
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
