<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }} - CareSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                size: auto;
                margin: 0;
            }
            body {
                -webkit-print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
            
            /* A4 Default */
            .print-container {
                width: 100%;
                max-width: 210mm;
                min-height: 297mm;
                padding: 15mm;
                margin: 0 auto;
                background: white;
            }

            /* Thermal Mode Override */
            body.thermal-mode .print-container {
                width: 80mm; /* Standard Thermal Width */
                min-height: auto;
                padding: 5mm;
                font-family: 'Courier New', Courier, monospace; /* Classic Receipt Font */
                font-size: 12px;
            }
            body.thermal-mode .header-full { display: none; }
            body.thermal-mode .header-thermal { display: block !important; }
            body.thermal-mode table th { display: none; } /* Hide headers in thermal */
            body.thermal-mode table td { 
                display: block; 
                text-align: left !important; 
                padding: 2px 0;
                border: none;
            }
            body.thermal-mode table tr { 
                border-bottom: 1px dashed #000; 
                display: block;
                padding: 5px 0;
            }
            body.thermal-mode .item-row {
                display: flex !important;
                justify-content: space-between;
                width: 100%;
            }
            body.thermal-mode .thermal-qty { display: inline-block; margin-right: 5px; font-weight: bold;}
        }
        
        /* Partial visibility helpers */
        .header-thermal { display: none; }
    </style>
</head>
<body class="bg-gray-100 print:bg-white text-sm" id="printBody">

    <div class="print-container rounded-sm mx-auto bg-white shadow-lg my-5 transition-all duration-300">
        
        {{-- Thermal Header (Hidden on Desktop/A4) --}}
        <div class="header-thermal text-center mb-4 border-b border-black pb-2">
            <h2 class="text-xl font-bold uppercase">CareSync Clinic</h2>
            <p class="text-xs">123 Medical Plaza, NY</p>
            <p class="text-xs">Tel: (555) 123-4567</p>
            <div class="my-2 border-t border-dashed border-black"></div>
            <p class="text-xs font-bold">INV: #{{ $invoice->id }}</p>
            <p class="text-xs">{{ $invoice->issued_at->format('d/m/Y H:i') }}</p>
        </div>

        {{-- Standard Header --}}
        <div class="header-full flex justify-between items-start border-b border-gray-200 pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                    @if($invoice->category === 'pharmacy')
                        PHARMACY RECEIPT
                    @elseif($invoice->category === 'lab')
                        LABORATORY BILL
                    @else
                        INVOICE
                    @endif
                </h1>
                <p class="text-gray-500 mt-1">#INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                <div class="badge mt-2 {{ $invoice->status === 'paid' ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }} inline-block px-3 py-1 rounded-full text-sm font-semibold uppercase">
                    {{ $invoice->status }}
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-indigo-600">CareSync</h2>
                <p class="text-gray-500 text-sm mt-1">123 Medical Plaza<br>New York, NY 10001</p>
                <p class="text-gray-500 text-sm">support@caresync.com</p>
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
                        <span class="font-medium text-gray-800">
                            @if($invoice->appointment)
                                Dr. {{ $invoice->appointment->doctor->name ?? 'Unknown' }}
                            @elseif($invoice->ipdAdmission)
                                Dr. {{ $invoice->ipdAdmission->doctor->name ?? 'Unknown' }}
                            @else
                                --
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Department:</span>
                        <span class="font-medium text-gray-800">
                            @if($invoice->appointment)
                                {{ $invoice->appointment->department->name ?? 'General' }}
                            @elseif($invoice->category == 'ipd')
                                IPD / Ward
                            @else
                                {{ ucfirst($invoice->category ?? 'General') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <table class="w-full mb-8">
            <thead class="header-full">
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
                            <div class="item-row">
                                <div>
                                    <span class="thermal-qty hidden">{{ $item->quantity }}x</span>
                                    <span class="font-medium">{{ $item->description }}</span>
                                </div>
                                <span class="hidden thermal-price font-bold">${{ number_format($item->total_price, 2) }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right header-full">{{ $item->quantity }}</td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right header-full">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-800 text-right header-full">${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                @else
                     <tr>
                        <td class="py-4 px-4 text-sm text-gray-800">
                             <div class="item-row">
                                <span>Medical Services</span>
                                <span class="hidden thermal-price font-bold">${{ number_format($invoice->amount, 2) }}</span>
                             </div>
                        </td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right header-full">1</td>
                        <td class="py-4 px-4 text-sm text-gray-600 text-right header-full">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="py-4 px-4 text-sm font-medium text-gray-800 text-right header-full">${{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="py-6 px-4 text-right header-full">
                        <span class="text-sm font-medium text-gray-500">Total Amount</span>
                    </td>
                    <!-- Thermal Total -->
                    <td class="py-6 px-4 text-right w-full block print:py-2">
                         <div class="flex justify-between items-center w-full">
                            <span class="header-thermal font-bold uppercase">Total:</span>
                            <span class="text-2xl font-bold text-indigo-600 print:text-black print:text-xl">${{ number_format($invoice->amount, 2) }}</span>
                         </div>
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Footer --}}
        <div class="border-t border-gray-200 pt-8 mt-8">
            <div class="flex justify-between items-center text-sm text-gray-500">
                <p>Thank you for choosing CareSync.</p>
                <div class="flex gap-4">
                     @if($invoice->transaction_id)
                        <span class="font-mono bg-gray-100 px-2 py-1 rounded">Trans ID: {{ $invoice->transaction_id }}</span>
                     @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-8 flex justify-center gap-4 no-print flex-wrap">
            <button onclick="printStandard()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print A4
            </button>
            <button onclick="printThermal()" class="px-6 py-3 bg-gray-800 text-white font-medium rounded-lg hover:bg-gray-900 shadow-md transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Print Thermal (POS)
            </button>
            @if($invoice->status !== 'paid')
                <a href="{{ route('reception.invoices.checkout', $invoice) }}" class="px-6 py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 shadow-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Pay Online
                </a>
            @endif
            <a href="{{ route('reception.invoices.index') }}" class="px-6 py-3 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 shadow-sm transition flex items-center justify-center">
                Close
            </a>
        </div>

    </div>
    
    <script>
        function printStandard() {
            document.getElementById('printBody').classList.remove('thermal-mode');
            window.print();
        }
        
        function printThermal() {
            document.getElementById('printBody').classList.add('thermal-mode');
            window.print();
             // Optional: Remove class after print dialog closes, but often better to leave it until refresh
             // setTimeout(() => document.getElementById('printBody').classList.remove('thermal-mode'), 1000);
        }
    </script>
</body>
</html>
