@extends('layouts.dashboard')

@section('header', 'Doctor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    
    {{-- Header / Welcome --}}
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                Hello, Dr. {{ auth()->user()->name }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                Type: {{ auth()->user()->doctorProfile->specialization ?? 'General Practitioner' }} | Today is {{ now()->format('l, M d, Y') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
             <a href="{{ route('doctor.appointments') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                View Full Calendar
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
        <!-- Card 1 -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-3 shadow-lg shadow-indigo-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">Today's Appointments</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $todayAppointmentsCount }} Scheduled</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('doctor.appointments') }}" class="font-medium text-indigo-700 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">Manage schedule &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 shadow-lg shadow-green-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">Total Patients</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">{{ $totalPatientsCount }} Assigned</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('doctor.patients') }}" class="font-medium text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors">View patient directory &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-3 shadow-lg shadow-purple-500/30">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">Consultations</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-white">Start Session</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
             <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('doctor.appointments') }}" class="font-medium text-purple-700 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 transition-colors">Begin consultations &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left: Schedule --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="px-4 py-5 border-b border-gray-200 dark:border-slate-700 sm:px-6 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Today's Schedule</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                        {{ $todaySchedule->count() }} Upcoming
                    </span>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    @if($todaySchedule->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($todaySchedule as $index => $appt)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-slate-600" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center ring-8 ring-white dark:ring-slate-800 text-white font-bold text-xs">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</p>
                                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                        <a href="{{ route('doctor.appointments.show', $appt->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                            {{ $appt->patient->name ?? 'Unknown Patient' }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ $appt->reason_for_visit ?? 'Routine Checkup' }}</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500 grid place-content-center">
                                                     <a href="{{ route('doctor.appointments.show', $appt->id) }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-md transition-colors">
                                                        Start &rarr;
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No appointments scheduled</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">You are free for the rest of the day.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Recent Vitals --}}
        <div>
            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl border border-gray-200 dark:border-slate-700">
                <div class="px-4 py-5 border-b border-gray-200 dark:border-slate-700 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Vitals</h3>
                </div>
                <div class="flow-root">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-slate-700">
                        @forelse($recentVitals as $vital)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                    {{ $vital->patient->name }}
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                                        {{ $vital->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex text-sm text-gray-500 dark:text-slate-400">
                                    <p class="flex items-center">
                                       <span class="font-bold mr-1">BP:</span> {{ $vital->blood_pressure ?? 'N/A' }}
                                    </p>
                                    <p class="mt-2 flex items-center text-sm text-gray-500 dark:text-slate-400 sm:mt-0 sm:ml-4">
                                        <span class="font-bold mr-1">T:</span> {{ $vital->temperature ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="px-4 py-10 text-center text-gray-500 dark:text-slate-400 text-sm">
                            No vitals recorded recently.
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-4 py-4 sm:px-6 text-center rounded-b-xl">
                    <a href="{{ route('doctor.appointments') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">View all patients</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
