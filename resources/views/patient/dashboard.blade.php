@extends('layouts.dashboard')

@section('header', 'My Health Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
    
    {{-- Welcome & Quick Actions --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500">Here is your health overview.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
             <a href="{{ route('chat.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Messages
            </a>
             <a href="{{ route('patient.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Book Appointment
            </a>
        </div>
    </div>

    {{-- Vitals Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Heart Rate --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">💓</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Heart Rate</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $latestVitals->heart_rate ?? '--' }} <span class="text-xs text-gray-500">bpm</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blood Pressure --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🩸</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Blood Pressure</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $latestVitals->blood_pressure_systolic ?? '--' }}/{{ $latestVitals->blood_pressure_diastolic ?? '--' }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Weight --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">⚖️</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Weight</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $latestVitals->weight ?? '--' }} <span class="text-xs text-gray-500">kg</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Temperature --}}
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl">🌡️</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Temperature</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $latestVitals->temperature ?? '--' }} <span class="text-xs text-gray-500">°C</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- Recent Appointments --}}
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Appointments</h3>
                <a href="{{ route('patient.appointments.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
            </div>
            <ul class="divide-y divide-gray-200">
                @forelse($appointments as $apt)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                {{ $apt->doctor->name ?? 'Unassigned' }}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $apt->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $apt->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $apt->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($apt->status) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    📅 {{ $apt->appointment_date ? $apt->appointment_date->format('M d, Y') : '-' }} • 🕒 {{ $apt->appointment_time }}
                                </p>
                            </div>
                             <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <a href="{{ route('patient.appointments.show', $apt) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View Details &rarr;</a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 text-sm text-gray-500 text-center">No appointments found.</li>
                @endforelse
            </ul>
        </div>

        {{-- Medical & Prescriptions Overview --}}
        <div class="space-y-6">
            
            {{-- Prescriptions --}}
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Prescriptions</h3>
                    <a href="{{ route('patient.prescriptions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($prescriptions as $p)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-900">{{ $p->medicine_name }}</span>
                                <span class="text-sm text-gray-500">{{ $p->dosage }}</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Dr. {{ $p->diagnosis->doctor->name ?? 'Unknown' }} • {{ $p->created_at->format('M d') }}</p>
                        </li>
                    @empty
                         <li class="px-4 py-4 text-sm text-gray-500 text-center">No active prescriptions.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Lab Reports --}}
             <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Lab Reports</h3>
                    {{-- Access records using the public route with this user --}}
                     <a href="{{ route('lab-reports.index', auth()->user()->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                </div>
                <ul class="divide-y divide-gray-200">
                    @forelse($labReports as $report)
                         <li class="px-4 py-4 sm:px-6 flex justify-between items-center">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $report->title }}</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ strtoupper($report->file_type) }}</span>
                            </div>
                            <a href="{{ route('lab-reports.download', $report) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                        </li>
                    @empty
                         <li class="px-4 py-4 text-sm text-gray-500 text-center">No records found.</li>
                    @endforelse
                </ul>
            </div>
            
        </div>
    </div>
</div>
@endsection
