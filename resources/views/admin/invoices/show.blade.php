@extends('layouts.dashboard')

@section('header', 'Invoice Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('messages.back_to_invoices') ?? 'Back to Invoices' }}
        </a>
    </div>

    <!-- Invoice Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Invoice #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-sm text-gray-500 mt-1">Created on {{ $invoice->created_at->format('F d, Y') }}</p>
            </div>
            <div>
                @php
                    $statusColors = [
                        'paid' => 'bg-green-100 text-green-800 border-green-200',
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                @endphp
                <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full border {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>

        <!-- Patient & Doctor Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">{{ __('messages.patient_info') ?? 'Patient Information' }}</h4>
                <div class="space-y-2">
                    <p class="text-lg font-semibold text-gray-900">{{ optional($invoice->appointment->patient)->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ optional($invoice->appointment->patient)->email ?? '' }}</p>
                    <p class="text-sm text-gray-600">{{ optional($invoice->appointment->patient)->phone ?? '' }}</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">{{ __('messages.doctor_info') ?? 'Doctor Information' }}</h4>
                <div class="space-y-2">
                    <p class="text-lg font-semibold text-gray-900">{{ optional($invoice->appointment->doctor)->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">{{ optional($invoice->appointment->doctor)->email ?? '' }}</p>
                    @if($invoice->appointment && $invoice->appointment->appointment_date)
                        <p class="text-sm text-gray-600">
                            Appointment: {{ \Carbon\Carbon::parse($invoice->appointment->appointment_date)->format('M d, Y') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="px-6 pb-6">
            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-4">{{ __('messages.invoice_items') ?? 'Invoice Items' }}</h4>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('messages.description') ?? 'Description' }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.quantity') ?? 'Qty' }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.unit_price') ?? 'Unit Price' }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.total') ?? 'Total' }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($invoice->items as $index => $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->type === 'medicine' ? optional($item->medicine)->name : $item->description }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($item->type) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <p>No itemized details available.</p>
                                    <p class="text-sm mt-1">Total Amount: <span class="font-semibold">${{ number_format($invoice->amount, 2) }}</span></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-semibold text-gray-700 uppercase">{{ __('messages.grand_total') ?? 'Grand Total' }}</td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-indigo-600">${{ number_format($invoice->amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Info -->
        @if($invoice->status === 'paid')
            <div class="px-6 pb-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-green-800 font-medium">Payment Received</span>
                    </div>
                    @if($invoice->paid_at)
                        <p class="text-sm text-green-700 mt-2">
                            Paid on {{ \Carbon\Carbon::parse($invoice->paid_at)->format('F d, Y \a\t h:i A') }}
                        </p>
                    @endif
                    @if($invoice->payment_method)
                        <p class="text-sm text-green-700">Payment Method: {{ ucfirst($invoice->payment_method) }}</p>
                    @endif
                    @if($invoice->transaction_id)
                        <p class="text-sm text-green-700">Transaction ID: {{ $invoice->transaction_id }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
