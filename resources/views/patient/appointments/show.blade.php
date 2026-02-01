@extends('layouts.dashboard')
@section('header', 'Medical Record')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- Controls --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('patient.appointments.index') }}" class="text-gray-500 hover:text-gray-900 font-medium flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to List
        </a>
        <div class="flex space-x-3">
             @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                <a href="{{ route('telemedicine.join', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Join Video Call
                </a>
            @endif
             @if($appointment->diagnosis)
                 <a href="{{ route('doctors.prescription.print', $appointment->diagnosis) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                    Print Visit Summary
                </a>
            @else
                 <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                    Print Page
                </button>
            @endif
             @if(auth()->check() && in_array(auth()->user()->role->slug, ['nurse','doctor']))
                <a href="{{ route('vitals.create', $appointment->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Vitals
                </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        
        {{-- Header Banner --}}
        <div class="bg-indigo-600 px-8 md:px-16 py-12 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <span class="inline-block py-1 px-3 rounded-full bg-indigo-500 bg-opacity-50 text-xs font-bold tracking-wider uppercase mb-2">
                        {{ $appointment->status }}
                    </span>
                    <h1 class="text-3xl font-extrabold tracking-tight">Appointment #{{ $appointment->id }}</h1>
                    <p class="text-indigo-200 mt-1 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $appointment->appointment_date ? $appointment->appointment_date->format('F d, Y') : 'Date TBD' }} 
                        <span class="mx-2">•</span> 
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $appointment->appointment_time }}
                    </p>
                </div>
                <div class="mt-4 md:mt-0 text-left md:text-right">
                    <div class="flex items-center justify-start md:justify-end">
                        <div class="text-left md:text-right mr-4">
                            <p class="text-lg font-bold">{{ $appointment->doctor->name ?? 'Unassigned' }}</p>
                            <p class="text-indigo-200 text-sm">{{ $appointment->doctor->doctorProfile->specialization ?? 'General' }}</p>
                        </div>
                        <div class="h-12 w-12 rounded-full bg-white text-indigo-600 flex items-center justify-center font-bold text-xl">
                            {{ substr($appointment->doctor->name ?? 'U', 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-200">
            
            {{-- Column 1: Vitals & Stats (Left) --}}
            <div class="col-span-1 p-8 md:p-16 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Patient Vitals
                </h3>
                
                @if($appointment->vitals->isNotEmpty())
                    @foreach($appointment->vitals as $vital)
                         <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">❤️</span>
                                    <div>
                                        <p class="text-xs text-gray-500">Heart Rate</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $vital->pulse ?? '--' }} <span class="text-xs font-normal text-gray-500">bpm</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">🩸</span>
                                    <div>
                                        <p class="text-xs text-gray-500">Blood Pressure</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $vital->blood_pressure ?? '--' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-2xl mr-3">🌡️</span>
                                    <div>
                                        <p class="text-xs text-gray-500">Temperature</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $vital->temperature ?? '--' }} <span class="text-xs font-normal text-gray-500">°C</span></p>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="mt-6 pt-6 border-t border-gray-200 text-xs text-gray-400">
                             Recorded by {{ $vital->recorder->name ?? 'Staff' }} at {{ $vital->created_at->format('H:i') }}
                         </div>
                         @break
                    @endforeach
                @else
                    <p class="text-sm text-gray-500 italic">No vitals recorded yet.</p>
                @endif
                
                {{-- Lab Reports List Small --}}
                <h3 class="text-lg font-bold text-gray-900 mt-12 mb-6 flex items-center">
                    <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    Attachments
                </h3>
                @if($appointment->labReports->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach($appointment->labReports as $report)
                            <li class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="text-sm font-medium text-gray-700 truncate w-24 md:w-32">{{ $report->title }}</span>
                                </div>
                                <a href="{{ route('lab-reports.download', $report) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-bold">Download</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                     <p class="text-sm text-gray-500 italic">No files attached.</p>
                @endif
            </div>

            {{-- Column 2 & 3: Clinical Notes (Right) --}}
            <div class="col-span-1 md:col-span-2 p-8 md:p-16">
                
                {{-- Diagnosis --}}
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Diagnosis & Clinical Notes
                    </h3>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md">
                        @if($appointment->diagnosis)
                            <p class="text-gray-800 text-lg leading-relaxed font-semibold">{{ $appointment->diagnosis->diagnosis }}</p>
                            @if($appointment->diagnosis->notes)
                                <p class="text-gray-600 mt-2 text-sm">{{ $appointment->diagnosis->notes }}</p>
                            @endif
                            @if($appointment->diagnosis->symptoms)
                                <div class="mt-3 pt-3 border-t border-yellow-200">
                                    <span class="text-xs font-bold text-yellow-800 uppercase">Symptoms reported:</span>
                                    <span class="text-sm text-yellow-900">{{ $appointment->diagnosis->symptoms }}</span>
                                </div>
                            @endif
                        @else
                             <p class="text-gray-500 italic">Doctor has not entered a diagnosis yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Prescription Table --}}
                 <div class="mt-12">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Prescribed Medication
                    </h3>
                    
                    @if($appointment->diagnosis && $appointment->diagnosis->prescriptions->isNotEmpty())
                        <div class="overflow-hidden border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosage</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($appointment->diagnosis->prescriptions as $p)
                                        <tr>
                                            <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $p->medicine_name }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $p->dosage }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ $p->duration }}</td>
                                            <td class="px-4 py-3 text-sm text-right">
                                                @if(auth()->check() && auth()->user()->role->slug === 'patient')
                                                    @if(!$p->refill_requested)
                                                        <form action="{{ route('patient.prescription.refill', $p) }}" method="POST">
                                                            @csrf
                                                            <button class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline">Request Refill</button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs font-bold text-amber-500">Refill Pending</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @if($p->instructions)
                                            <tr class="bg-gray-50">
                                                <td colspan="3" class="px-4 py-2 text-xs text-gray-500 italic">
                                                    Note: {{ $p->instructions }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                         <div class="rounded-md bg-gray-50 p-4 border border-gray-200 text-center text-gray-500">
                             No instructions or medications.
                         </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
