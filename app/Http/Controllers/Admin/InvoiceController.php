<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of all invoices for admin.
     */
    public function index(Request $request)
    {
        // 1. Base Query (Common filters for Stats AND List)
        // We do NOT apply 'status' here, because stats need to show breakdown (Paid vs Pending) 
        // regardless of whether the user is viewing the 'Pending' list.
        $baseQuery = Invoice::query();

        // Filter by Category
        if ($request->filled('category')) {
            $baseQuery->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $baseQuery->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $baseQuery->whereDate('created_at', '<=', $request->to_date);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $baseQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('appointment.patient', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Calculate Stats based on the Base Scope (e.g., All OPD invoices)
        $totalRevenue = (clone $baseQuery)->where('status', 'paid')->sum('amount');
        $pendingAmount = (clone $baseQuery)->where('status', 'pending')->sum('amount');
        $totalInvoices = (clone $baseQuery)->count();
        $paidInvoices = (clone $baseQuery)->where('status', 'paid')->count();

        // 3. List Query (Base + Status Filter)
        $listQuery = (clone $baseQuery)->with(['appointment.patient', 'appointment.doctor', 'items']);
        
        if ($request->filled('status')) {
            $listQuery->where('status', $request->status);
        }

        $invoices = $listQuery->latest()->paginate(15)->withQueryString();

        return view('admin.invoices.index', [
            'invoices' => $invoices,
            'totalRevenue' => $totalRevenue,
            'pendingAmount' => $pendingAmount,
            'totalInvoices' => $totalInvoices,
            'paidInvoices' => $paidInvoices,
            'currentCategory' => $request->category, // Pass this to view for dynamic titling
        ]);
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['appointment.patient', 'appointment.doctor', 'items.medicine']);
        
        return view('admin.invoices.show', [
            'invoice' => $invoice,
        ]);
    }
}
