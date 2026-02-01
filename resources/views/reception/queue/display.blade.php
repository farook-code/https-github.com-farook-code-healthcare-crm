<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Queue Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Auto-refresh every 30 seconds --}}
    <meta http-equiv="refresh" content="30">
    <style>
        body { background-color: #0f172a; color: white; overflow: hidden; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="h-screen w-screen flex flex-col p-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-12 border-b border-indigo-500/30 pb-6">
        <h1 class="text-5xl font-bold tracking-tight text-indigo-400">CareSync Clinic</h1>
        <div class="text-right">
            <p class="text-3xl font-light">{{ now()->format('h:i A') }}</p>
            <p class="text-lg text-slate-400">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <div class="flex-1 grid grid-cols-12 gap-12">
        
        {{-- Left: Now Serving (Large) --}}
        <div class="col-span-7 flex flex-col gap-6">
            <h2 class="text-2xl font-semibold text-emerald-400 uppercase tracking-widest pl-2 mb-2">Now Serving</h2>
            
            @forelse($serving as $appointment)
                <div class="glass rounded-3xl p-10 flex justify-between items-center border-l-8 border-emerald-500 shadow-2xl animate-pulse">
                    <div>
                        <p class="text-slate-400 text-xl font-medium mb-1">Patient Ticket</p>
                        <h3 class="text-7xl font-bold text-white tracking-wider">
                            {{ $appointment->patient->patient_code ?? 'P-'.$appointment->patient_id }}
                        </h3>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-400 text-xl font-medium mb-2">Proceed To</p>
                        <div class="bg-indigo-600 px-6 py-2 rounded-xl inline-block">
                            <span class="text-4xl font-bold">Room {{ $appointment->doctor->id }}</span>
                        </div>
                        <p class="mt-3 text-lg text-indigo-300">Dr. {{ $appointment->doctor->user->name }}</p>
                    </div>
                </div>
            @empty
                <div class="glass rounded-3xl p-16 text-center border-l-8 border-slate-700">
                    <p class="text-3xl text-slate-500">Please Wait...</p>
                    <p class="text-slate-600 mt-2">Doctor will be with you shortly</p>
                </div>
            @endforelse

        </div>

        {{-- Right: Up Next (List) --}}
        <div class="col-span-5 flex flex-col">
            <h2 class="text-2xl font-semibold text-blue-400 uppercase tracking-widest pl-2 mb-8">Up Next</h2>
            
            <div class="space-y-4">
                @forelse($waiting as $appointment)
                    <div class="glass p-6 rounded-2xl flex justify-between items-center">
                        <div>
                            <span class="block text-3xl font-bold text-slate-200">
                                {{ $appointment->patient->patient_code ?? 'P-'.str_pad($appointment->patient_id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                             <span class="text-sm text-slate-500 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                {{ $appointment->doctor->name }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="block text-2xl font-mono text-blue-400">
                               {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                            </span>
                            <span class="text-xs text-slate-500 uppercase tracking-widest">Scheduled</span>
                        </div>
                    </div>
                @empty
                    <div class="text-slate-500 text-xl italic p-4">No patients in waiting list.</div>
                @endforelse
            </div>
            
            {{-- Footer Info --}}
            <div class="mt-auto glass p-6 rounded-xl bg-indigo-900/20 border-indigo-500/20">
                <div class="flex items-start gap-4">
                    <svg class="w-8 h-8 text-indigo-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <h4 class="font-bold text-lg text-indigo-200">Patient Reminder</h4>
                        <p class="text-slate-400 text-sm mt-1">Please have your ID and Insurance Card ready before entering the doctor's room.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
