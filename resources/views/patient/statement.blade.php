<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Financial Statement - {{ $patient->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { font-size: 12px; }
            .bg-gray-50 { background-color: #fff !important; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 p-8">

    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8 print:shadow-none print:p-0">
        
        {{-- Header --}}
        <div class="flex justify-between items-start mb-8 border-b pb-6">
            <div>
                <h1 class="text-2xl font-bold text-indigo-900">Medical Expense Statement</h1>
                <p class="text-gray-500 text-sm mt-1">Generated on {{ date('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <h2 class="font-bold text-gray-900">{{ config('app.name') }}</h2>
                <p class="text-sm text-gray-500">123 Health Avenue</p>
                <p class="text-sm text-gray-500">Medical District, NY 10001</p>
                <p class="text-sm text-gray-500">Phone: (555) 123-4567</p>
            </div>
        </div>

        {{-- Patient Details --}}
        <div class="mb-8 grid grid-cols-2 gap-8 bg-gray-50 p-4 rounded-lg border print:border-none print:p-0">
            <div>
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Patient Details</h3>
                <p class="font-bold text-lg text-gray-900 mt-1">{{ $patient->name }}</p>
                <p class="text-sm text-gray-600">ID: #PT-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</p>
                @if($patient->date_of_birth)
                <p class="text-sm text-gray-600">DOB: {{ $patient->date_of_birth->format('M d, Y') }}</p>
                @endif
            </div>
            <div class="text-right">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide">Insurance Details</h3>
                <p class="font-bold text-gray-900 mt-1">{{ $patient->insurance_provider ?? 'Self Pay' }}</p>
                <p class="text-sm text-gray-600">Policy #: {{ $patient->policy_number ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Statement Table --}}
        <table class="w-full mb-8 text-left border-collapse">
            <thead>
                <tr>
                    <th class="border-b-2 border-gray-200 py-3 text-sm font-bold text-gray-600 uppercase">Date</th>
                    <th class="border-b-2 border-gray-200 py-3 text-sm font-bold text-gray-600 uppercase">Invoice #</th>
                    <th class="border-b-2 border-gray-200 py-3 text-sm font-bold text-gray-600 uppercase">Description / Doctor</th>
                    <th class="border-b-2 border-gray-200 py-3 text-sm font-bold text-gray-600 uppercase text-right">Billed</th>
                    <th class="border-b-2 border-gray-200 py-3 text-sm font-bold text-gray-600 uppercase text-right">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $totalBilled = 0; $totalPaid = 0; @endphp
                @foreach($invoices as $invoice)
                    @php 
                        $totalBilled += $invoice->amount; 
                        if($invoice->status == 'paid') $totalPaid += $invoice->amount;
                    @endphp
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-sm text-gray-700">{{ $invoice->created_at->format('M d, Y') }}</td>
                        <td class="py-3 text-sm text-indigo-600 font-medium font-mono">#INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3 text-sm text-gray-700">
                            <div class="font-medium">{{ $invoice->appointment->doctor->name ?? 'Medical Services' }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($invoice->items->pluck('description')->join(', '), 50) }}</div>
                        </td>
                        <td class="py-3 text-sm text-gray-900 font-bold text-right">${{ number_format($invoice->amount, 2) }}</td>
                        <td class="py-3 text-sm text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="pt-6 text-right font-bold text-gray-600">Total Billed:</td>
                    <td class="pt-6 text-right font-bold text-gray-900 text-lg">${{ number_format($totalBilled, 2) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="pt-2 text-right font-bold text-gray-600">Total Paid:</td>
                    <td class="pt-2 text-right font-bold text-green-600 text-lg">${{ number_format($totalPaid, 2) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="pt-2 text-right font-bold text-gray-600">Balance Due:</td>
                    <td class="pt-2 text-right font-bold text-red-600 text-lg">${{ number_format($totalBilled - $totalPaid, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Footer/Disclaimer --}}
        <div class="border-t pt-6 text-center text-xs text-gray-400">
            <p>This statement is for insurance and reimbursement purposes.</p>
            <p>Generated electronically by {{ config('app.name') }}. No signature required.</p>
        </div>

        {{-- Print Action --}}
        <div class="mt-8 text-center no-print">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-indigo-700 transition">
                Print Statement
            </button>
            <a href="{{ url()->previous() }}" class="ml-4 text-gray-500 hover:text-gray-700 underline">Back to Dashboard</a>
        </div>

    </div>

</body>
</html>
