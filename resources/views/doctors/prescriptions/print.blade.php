<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prescription #{{ $diagnosis->id }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            body { background-color: white; padding: 0; }
            .no-print { display: none !important; }
            .page-sheet { box-shadow: none !important; margin: 0; width: 100%; border: none; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10 min-h-screen text-slate-800">

    <!-- Actions -->
    <div class="max-w-[210mm] mx-auto mb-6 flex justify-end gap-3 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print Prescription
        </button>
        <button onclick="window.close()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 font-medium shadow-sm">
            Close
        </button>
    </div>

    <!-- Paper Sheet (A4 approx) -->
    <div class="page-sheet bg-white max-w-[210mm] mx-auto p-[15mm] shadow-lg rounded-none sm:rounded-sm min-h-[297mm] flex flex-col relative">
        
        <!-- Header -->
        <header class="border-b-2 border-slate-800 pb-6 mb-8 flex justify-between items-start">
            <div>
                <div class="text-3xl font-extrabold tracking-tight text-slate-900 uppercase">HealthFlow</div>
                <div class="text-sm text-slate-500 font-medium mt-1">Medical Center & Diagnostics</div>
                <div class="text-xs text-slate-400 mt-2">
                    123 Healthcare Ave, Medical District<br>
                    New York, NY 10001 • (555) 123-4567
                </div>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold text-slate-800">{{ $diagnosis->doctor->name }}</div>
                <div class="text-sm text-slate-600 italic">
                    {{ optional($diagnosis->doctor->doctorProfile)->qualification ?? 'MBBS' }}
                </div>
                <div class="text-sm font-semibold text-blue-600 mt-1 uppercase tracking-wide">
                    {{ optional(optional($diagnosis->doctor->doctorProfile)->department)->name ?? 'General' }}
                </div>
            </div>
        </header>

        <!-- Patient Details -->
        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div>
                <span class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Patient Name</span>
                <span class="text-lg font-semibold text-slate-900 border-b border-dotted border-slate-300 pb-1 block w-full">
                    {{ optional($diagnosis->patient)->name ?? 'Unknown' }}
                </span>
            </div>
            <div>
                <span class="block text-xs uppercase tracking-wider text-slate-400 font-bold mb-1">Date</span>
                <span class="text-lg font-semibold text-slate-900 border-b border-dotted border-slate-300 pb-1 block w-full">
                    {{ $diagnosis->created_at->format('d M, Y') }}
                </span>
            </div>
        </div>

        <!-- Diagnosis Section -->
        <div class="mb-8">
            <h3 class="font-bold text-slate-900 uppercase text-xs tracking-wider border-b border-slate-200 pb-2 mb-3">Diagnosis</h3>
            <p class="text-slate-800 font-medium leading-relaxed">
                {{ $diagnosis->diagnosis }}
            </p>
            @if($diagnosis->symptoms)
                <p class="text-sm text-slate-500 mt-2">
                    <span class="font-semibold">Symptoms:</span> {{ $diagnosis->symptoms }}
                </p>
            @endif
        </div>

        <!-- Rx Section -->
        <div class="flex-1">
            <div class="text-4xl font-serif font-bold text-slate-900 mb-4 italic">Rx</div>
            
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="py-2 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200 w-1/2">Medicine</th>
                        <th class="py-2 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Dosage</th>
                        <th class="py-2 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Duration</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($diagnosis->prescriptions as $p)
                        <tr>
                            <td class="py-3 pr-4">
                                <div class="font-bold text-slate-800">{{ $p->medicine_name }}</div>
                                @if($p->instructions)
                                    <div class="text-xs text-slate-500 italic mt-0.5">{{ $p->instructions }}</div>
                                @endif
                            </td>
                            <td class="py-3 text-sm font-medium text-slate-700">{{ $p->dosage }}</td>
                            <td class="py-3 text-sm font-medium text-slate-700">{{ $p->duration }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 italic">No medicines prescribed.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Signature -->
        <div class="mt-12 pt-8 border-t-2 border-slate-100 flex justify-between items-end">
            <div class="text-xs text-slate-400 w-1/2">
                This is a computer-generated document. No signature is required.<br>
                Generated by HealthFlow CRM on {{ now() }}
            </div>
            <div class="text-center w-1/3">
                <div class="h-16 mb-2 flex items-end justify-center">
                    <!-- Placeholder for e-signature -->
                    <span class="font-script text-2xl text-slate-400 opacity-50 font-serif italic">Signed</span>
                </div>
                <div class="border-t border-slate-800 pt-2 font-bold text-slate-900 text-sm">
                    Dr. {{ $diagnosis->doctor->name }}
                </div>
            </div>
        </div>

    </div>

</body>
</html>
