@extends('layouts.dashboard')

@section('header', '')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    {{-- TOP BAR --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <a href="{{ route('doctor.appointments') }}" class="group flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <div class="mr-2 p-1 rounded-md group-hover:bg-indigo-50 transition">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </div>
            Back to List
        </a>
        
        <div class="flex items-center gap-2">
             <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-700 font-medium rounded-lg text-sm shadow-sm hover:bg-slate-50 transition">
                 <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                 Print
             </button>
             @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                <a href="{{ route('telemedicine.join', $appointment) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg text-sm shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Start Call
                </a>
            @endif
        </div>
    </div>

    {{-- PATIENT HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row gap-6 items-center md:items-start">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-2xl font-bold text-slate-500 shrink-0">
                {{ substr($appointment->patient->name, 0, 1) }}
            </div>
            <div class="flex-1 text-center md:text-left">
                 <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                     <span class="text-xs text-slate-400 font-mono">#{{ $appointment->id }}</span>
                     <span class="text-slate-300">•</span>
                     <span class="text-xs text-slate-500 font-medium">{{ $appointment->appointment_date->format('F d, Y') }} — {{ $appointment->appointment_time }}</span>
                 </div>
                 <h1 class="text-2xl font-bold text-slate-900 mb-2">{{ $appointment->patient->name }}</h1>
                 
                 <div class="flex flex-wrapjustify-center md:justify-start gap-4 text-sm text-slate-600">
                    <div class="flex items-center gap-1">
                        <span class="text-slate-400">Gender:</span>
                        <span class="font-semibold">{{ ucfirst($appointment->patient->gender ?? '-') }}</span>
                    </div>
                    <div class="bg-slate-200 w-px h-4 hidden md:block"></div>
                    <div class="flex items-center gap-1">
                        <span class="text-slate-400">Age:</span>
                        <span class="font-semibold">{{ $appointment->patient->dob ? \Carbon\Carbon::parse($appointment->patient->dob)->age : '-' }}</span>
                    </div>
                    <div class="bg-slate-200 w-px h-4 hidden md:block"></div>
                   <div class="flex items-center gap-1">
                        <span class="text-slate-400">Phone:</span>
                        <span class="font-semibold">{{ $appointment->patient->phone ?? '-' }}</span>
                    </div>
                 </div>

                 @if($appointment->patient->allergies || $appointment->patient->chronic_conditions)
                    <div class="mt-4 pt-4 border-t border-slate-50 flex flex-wrap gap-4">
                        @if($appointment->patient->allergies)
                            <div class="flex items-start gap-2">
                                <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-rose-100">Allergies</span>
                                <span class="text-sm text-slate-700">{{ $appointment->patient->allergies }}</span>
                            </div>
                        @endif
                         @if($appointment->patient->chronic_conditions)
                            <div class="flex items-start gap-2">
                                <span class="bg-amber-50 text-amber-600 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-amber-100">Conditions</span>
                                <span class="text-sm text-slate-700">{{ $appointment->patient->chronic_conditions }}</span>
                            </div>
                        @endif
                    </div>
                 @endif

            </div>
             <div class="mt-2 md:mt-0">
                 @if($appointment->status == 'completed')
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-100">
                        {{ $appointment->status }}
                    </span>
                @else
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-100">
                        {{ $appointment->status }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- VITALS --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-1 h-full bg-rose-500"></div>
        <div class="flex items-center justify-between mb-4 pl-3">
             <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Patient Vitals</h3>
             @if((auth()->user()->role->slug === 'nurse' || auth()->user()->role->slug === 'doctor') && $appointment->vitals->isEmpty())
                <a href="{{ route('vitals.create', $appointment->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-md transition">
                    + Record
                </a>
            @endif
        </div>
        
        @if($appointment->vitals->isNotEmpty())
            @foreach($appointment->vitals as $vital)
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pl-3">
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Heart Rate</span>
                        <span class="text-xl font-bold text-slate-800">{{ $vital->pulse }} <span class="text-xs font-normal text-slate-400">bpm</span></span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Blood Pressure</span>
                        <span class="text-xl font-bold text-slate-800">{{ $vital->blood_pressure }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Temperature</span>
                        <span class="text-xl font-bold text-slate-800">{{ $vital->temperature }} <span class="text-xs font-normal text-slate-400">°C</span></span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-0.5">Recorded</span>
                        <span class="text-xs font-medium text-slate-500">{{ $vital->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @break
            @endforeach
        @else
             <p class="text-sm text-slate-400 italic pl-3">No vitals recorded.</p>
        @endif
    </div>

    {{-- DIAGNOSIS --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
        
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center pl-8">
            <h3 class="font-bold text-slate-900">Diagnosis & Notes</h3>
            
            <div class="flex items-center gap-2">
                 {{-- AI Scribe Button --}}
                 @if($appointment->status !== 'completed' && auth()->user()->role->slug === 'doctor')
                    <div x-data="aiScribe()" class="relative">
                        <button @click="toggleRecording" 
                                :class="recording ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-md transition shadow-sm border border-transparent mr-2">
                            <svg x-show="!recording" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            <svg x-show="recording" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                            <span x-text="recording ? 'Listening...' : 'AI Scribe'"></span>
                        </button>
                        
                        {{-- Hidden Audio Input for fallback/testing --}}
                        <input type="file" x-ref="audioInput" class="hidden" accept="audio/*">
                    </div>

                    <a href="{{ route('doctors.appointments.diagnosis.form', $appointment) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-md hover:bg-indigo-700 transition">
                        + Add
                    </a>
                @endif
            </div>
            
             @if($appointment->diagnosis && auth()->user()->role->slug === 'doctor' && $appointment->status !== 'completed')
                <!-- Edit Button Logic (Kept existing) -->
             @endif
        </div>
             @if($appointment->diagnosis && auth()->user()->role->slug === 'doctor')
                <a href="{{ route('doctors.appointments.diagnosis.form', $appointment) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit
                </a>
            @endif
        </div>

        {{-- Content --}}
        <div class="p-6 pl-8">
            @if($appointment->diagnosis)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                    <div>
                        <span class="block text-xs text-indigo-500 uppercase font-bold tracking-wider mb-1">Primary Diagnosis</span>
                        <p class="text-xl font-bold text-slate-900 leading-tight">{{ $appointment->diagnosis->diagnosis }}</p>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 uppercase font-bold tracking-wider mb-1">Symptoms</span>
                        <p class="text-slate-700 text-sm leading-relaxed">{{ $appointment->diagnosis->symptoms ?? 'None reported' }}</p>
                    </div>
                </div>

                 @if($appointment->diagnosis->notes)
                    <div class="bg-amber-50 rounded-lg p-4 border border-amber-100">
                        <span class="block text-[10px] text-amber-600 uppercase font-bold tracking-wider mb-1">Doctor's Notes</span>
                        <p class="text-sm text-slate-800 leading-relaxed whitespace-pre-line">{{ $appointment->diagnosis->notes }}</p>
                    </div>
                @endif
            @else
                <div class="py-8 text-center bg-slate-50/30 rounded-lg border-2 border-slate-100 border-dashed">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-50 text-indigo-300 mb-2">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-sm text-slate-500 font-medium mb-3">No diagnosis recorded.</p>
                     @if($appointment->status !== 'completed' && auth()->user()->role->slug === 'doctor')
                        <a href="{{ route('doctors.appointments.diagnosis.form', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-md hover:bg-slate-50 transition shadow-sm">
                            Add Clinical Findings
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- MEDICATIONS --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
        
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center pl-8">
            <h3 class="font-bold text-slate-900">Medications</h3>
             @if($appointment->diagnosis && auth()->user()->role->slug === 'doctor')
                <a href="{{ route('doctors.prescription.form', $appointment->diagnosis) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-md hover:bg-slate-50 transition shadow-sm">
                    + Add Rx
                </a>
            @endif
        </div>

        @if($appointment->diagnosis && $appointment->diagnosis->prescriptions->isNotEmpty())
             <div class="overflow-x-auto">
                 <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-8">Medicine</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dosage</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Instructions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($appointment->diagnosis->prescriptions as $p)
                            <tr>
                                <td class="px-6 py-3 font-bold text-sm text-slate-800 pl-8">{{ $p->medicine_name }}</td>
                                <td class="px-6 py-3"><span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">{{ $p->dosage }}</span></td>
                                <td class="px-6 py-3 text-slate-600 text-sm">{{ $p->duration }}</td>
                                <td class="px-6 py-3 text-slate-500 italic text-sm">{{ $p->instructions ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
             <div class="p-8 text-center text-slate-400">
                @if(!$appointment->diagnosis)
                     <p class="text-sm">Please add a diagnosis before prescribing.</p>
                @else
                     <p class="text-sm">No prescriptions added.</p>
                     @if(auth()->user()->role->slug === 'doctor')
                        <a href="{{ route('doctors.prescription.form', $appointment->diagnosis) }}" class="block mt-2 text-emerald-600 font-bold text-sm hover:underline">
                            Add First Prescription
                        </a>
                     @endif
                @endif
            </div>
        @endif
    </div>

    {{-- VACCINATIONS & IMMUNIZATION --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative" x-data="{ openVaccine: false }">
        <div class="absolute top-0 left-0 w-1 h-full bg-indigo-400"></div>
        
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center pl-8">
            <h3 class="font-bold text-slate-900">Vaccinations</h3>
            @if(auth()->user()->role->slug === 'doctor' || auth()->user()->role->slug === 'nurse')
                <button @click="openVaccine = !openVaccine" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-md hover:bg-slate-50 transition shadow-sm">
                    + Add Vaccine
                </button>
            @endif
        </div>

        {{-- Add Vaccine Form --}}
        <div x-show="openVaccine" class="p-6 bg-slate-50 border-b border-slate-100" style="display: none;">
            <form action="{{ route('doctor.appointments.vaccinations.store', $appointment) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Vaccine Name</label>
                    <input type="text" name="vaccine_name" required class="w-full text-sm rounded-md border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="e.g. Tetanus">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Dose / Batch</label>
                    <input type="text" name="dose_number" class="w-full text-sm rounded-md border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="e.g. 1st Dose">
                </div>
                 <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Date Administered</label>
                    <input type="date" name="administered_date" value="{{ date('Y-m-d') }}" required class="w-full text-sm rounded-md border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700 transition">Save Record</button>
                </div>
            </form>
        </div>

        @if(isset($vaccinations) && $vaccinations->isNotEmpty())
             <div class="overflow-x-auto">
                 <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-8">Vaccine</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dose</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($vaccinations as $v)
                            <tr>
                                <td class="px-6 py-3 font-bold text-sm text-slate-800 pl-8">{{ $v->vaccine_name }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $v->dose_number ?? '-' }}</td>
                                <td class="px-6 py-3 text-slate-600 text-sm">{{ $v->administered_date->format('M d, Y') }}</td>
                                <td class="px-6 py-3 text-slate-500 italic text-sm">{{ $v->notes ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
             <div class="p-6 text-center text-slate-400 text-sm">
                No vaccination records found.
            </div>
        @endif
    </div>

    {{-- PATIENT HISTORY --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
        
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center pl-8">
            <h3 class="font-bold text-slate-900">Medical History</h3>
            <span class="text-xs font-medium text-slate-400">{{ isset($history) ? $history->count() : 0 }} Past Visits</span>
        </div>

        @if(isset($history) && $history->isNotEmpty())
             <div class="overflow-x-auto">
                 <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider pl-8">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Diagnosis</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($history as $h)
                            <tr>
                                <td class="px-6 py-3 font-semibold text-sm text-slate-700 pl-8">{{ $h->appointment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-3 text-sm text-slate-800 font-bold">{{ $h->diagnosis->diagnosis ?? 'No Diagnosis' }}</td>
                                <td class="px-6 py-3 text-slate-600 text-xs">Dr. {{ $h->doctor->name }}</td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('doctor.appointments.show', $h->id) }}" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase tracking-wide">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
             <div class="p-6 text-center text-slate-400 text-sm">
                No previous visits recorded in this system.
            </div>
        @endif
    </div>

    {{-- ATTACHMENTS --}}
    @if($appointment->labReports->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wide">Attachments</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                 @foreach($appointment->labReports as $report)
                    <a href="{{ route('lab-reports.download', $report) }}" class="flex items-center p-2.5 border border-slate-200 rounded-lg hover:border-indigo-500 transition group bg-white hover:shadow-sm">
                        <div class="w-8 h-8 rounded bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 mr-3 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="overflow-hidden min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate group-hover:text-indigo-600">{{ $report->title }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">{{ $report->file_type }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

     {{-- FOOTER ACTION: COMPLETE --}}
    @if($appointment->status !== 'completed' && auth()->user()->role->slug === 'doctor')
        <div class="flex justify-end pt-4">
             <form method="POST" action="{{ route('doctor.appointments.complete', $appointment) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md shadow-emerald-200 transition transform hover:-translate-y-0.5 text-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Finalize Appointment
                </button>
            </form>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function aiScribe() {
        return {
            recording: false,
            mediaRecorder: null,
            audioChunks: [],
            
            async toggleRecording() {
                if (!this.recording) {
                    this.startRecording();
                } else {
                    this.stopRecording();
                }
            },

            async startRecording() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.audioChunks = [];

                    this.mediaRecorder.ondataavailable = (event) => {
                        this.audioChunks.push(event.data);
                    };

                    this.mediaRecorder.onstop = () => {
                        const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                        this.sendAudio(audioBlob);
                        
                        // Stop all tracks
                        stream.getTracks().forEach(track => track.stop());
                    };

                    this.mediaRecorder.start();
                    this.recording = true;
                    console.log("Recording started...");

                } catch (err) {
                    console.error("Error accessing microphone:", err);
                    alert("Could not access microphone. Please allow permissions.");
                }
            },

            stopRecording() {
                if (this.mediaRecorder && this.recording) {
                    this.mediaRecorder.stop();
                    this.recording = false;
                    console.log("Recording stopped...");
                }
            },

            sendAudio(blob) {
                // Show loading state...
                
                let formData = new FormData();
                formData.append('audio', blob, 'recording.wav');
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('appointment_id', {{ $appointment->id }});

                fetch('{{ route('doctor.ai.scribe') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Audio processed! Notes generated.');
                        // Reload or dynamically append notes to the UI
                        window.location.reload(); 
                    } else {
                        alert('Error processing audio: ' + (data.message || 'Unknown'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Upload failed.');
                });
            }
        }
    }
</script>
@endpush
