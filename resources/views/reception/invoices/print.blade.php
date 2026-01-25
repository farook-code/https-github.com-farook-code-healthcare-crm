<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }} - Healthcare CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 print:p-0 print:bg-white">

    <div class="max-w-3xl mx-auto bg-white p-8 shadow-lg rounded-xl print:shadow-none">
        
        {{-- Header --}}
        <div class="flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">INVOICE</h1>
                <p class="text-gray-500 mt-1">#INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                <div class="badge mt-2 {{ $invoice->status === 'paid' ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }} inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    {{ $invoice->status }}
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-indigo-600">Healthcare CRM</h2>
                <p class="text-gray-500 text-sm mt-1">123 Medical Plaza<br>New York, NY 10001</p>
                <p class="text-gray-500 text-sm">support@healthcarecrm.com</p>
                <p class="text-gray-500 text-sm">+1 (555) 123-4567</p>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-gray-500 text-sm uppercase font-semibold mb-2">Bill To</h3>
                <p class="font-bold text-gray-800 text-lg">{{ $invoice->patient->user->name ?? 'Walk-in Patient' }}</p>
                <p class="text-gray-600 text-sm">{{ $invoice->patient->address ?? '' }}</p>
                <p class="text-gray-600 text-sm">{{ $invoice->patient->user->email ?? '' }}</p>
                <p class="text-gray-600 text-sm">{{ $invoice->patient->phone ?? '' }}</p>
            </div>
            <div class="text-right">
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Issued Date:</span>
                        <span class="font-medium text-gray-800">{{ $invoice->issued_at->format('M d, Y') }}</span>
                    </div>
                    @if($invoice->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Paid Date:</span>
                        <span class="font-medium text-gray-800">{{ $invoice->paid_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Doctor:</span>
                        <span class="font-medium text-gray-800">Dr. {{ $invoice->appointment->doctor->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Department:</span>
                        <span class="font-medium text-gray-800">{{ $invoice->appointment->department->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <table class="w-full mb-8">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                    <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($invoice->items->count() > 0)
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="py-4 px-4 text-sm text-gray-800">
                            <span class="font-medium">{{ $item->description }}</span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right">{{ $item->quantity }}</td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-800 text-right">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                    {{-- Legacy support for invoices created before we had items --}}
                     <tr>
                        <td class="py-4 px-4 text-sm text-gray-800">
                            <span class="font-medium">Medical Services (Flat Rate)</span>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right">1</td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-800 text-right">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="py-6 px-4 text-right">
                        <span class="text-sm font-medium text-gray-500">Total Amount</span>
                    </td>
                    <td class="py-6 px-4 text-right">
                        <span class="text-2xl font-bold text-indigo-600">${{ number_format($invoice->amount, 2) }}</span>
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Footer --}}
        <div class="border-t border-gray-200 pt-8 mt-8">
            <div class="flex justify-between items-center text-sm text-gray-500">
                <p>Thank you for choosing Healthcare CRM.</p>
                <div class="flex gap-4">
                     @if($invoice->transaction_id)
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded">Trans ID: {{ $invoice->transaction_id }}</span>
                     @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 flex justify-center gap-4 no-print">
            <button onclick="window.print()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Invoice
            </button>
            @if($invoice->status !== 'paid')
                <a href="{{ route('reception.invoices.checkout', $invoice) }}" class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 shadow-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Pay Online
                </a>
            @endif
            <button onclick="window.close()" class="px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 shadow-sm transition">
                Close
            </button>
        </div>

    </div>
</body>
</html>
