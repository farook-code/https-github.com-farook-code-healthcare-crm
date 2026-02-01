<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InvoiceExportController extends Controller
{
    /**
     * Export Invoices to CSV.
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');

        $query = Invoice::with(['patient', 'doctor']);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $invoices = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="invoices_export_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($invoices) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'Invoice ID',
                'Date',
                'Patient Name',
                'Doctor Name',
                'Total Amount',
                'Status',
                'Payment Method',
                'Transaction ID'
            ]);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->id,
                    $invoice->created_at->format('Y-m-d H:i:s'),
                    $invoice->patient ? $invoice->patient->name : 'N/A',
                    $invoice->doctor ? $invoice->doctor->name : 'N/A',
                    $invoice->total_amount,
                    ucfirst($invoice->status),
                    ucfirst($invoice->payment_method ?? 'N/A'),
                    $invoice->transaction_id ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
