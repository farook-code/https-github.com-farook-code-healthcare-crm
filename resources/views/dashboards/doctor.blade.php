@extends('layouts.dashboard')

@section('header', 'Doctor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Doctor Dashboard
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Welcome Dr. {{ auth()->user()->name }}
            </p>
        </div>
    </div>

    {{-- Top Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 mb-8">
        {{-- Appointments --}}
        <a href="{{ route('doctor.appointments') }}" class="block">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Appointments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">Manage</div>
                            </dd>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                        <span class="font-medium text-blue-600 hover:text-blue-500">View upcoming visits &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        {{-- Patients --}}
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Patients</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">My List</div>
                        </dd>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('doctor.patients') }}" class="font-medium text-blue-600 hover:text-blue-500">View assigned patients &rarr;</a>
                </div>
            </div>
        </div>

        {{-- Action --}}
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Today's Focus</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">Consult</div>
                        </dd>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-4 sm:px-6">
                <div class="text-sm">
                    <a href="{{ route('doctor.appointments') }}" class="font-medium text-blue-600 hover:text-blue-500">Start consultations &rarr;</a>
                </div>
            </div>
        </div>
        
        {{-- Messages --}}
        <a href="{{ route('chat.index') }}" class="block">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Messages</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">Inbox</div>
                            </dd>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="text-sm">
                         <span class="font-medium text-blue-600 hover:text-blue-500">View conversations &rarr;</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Recent Vitals --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Recent Patient Vitals
            </h3>
        </div>
        <div class="overflow-x-auto">
            @if($recentVitals->count())
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pulse</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SpO₂</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recorded At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentVitals as $vital)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $vital->patient->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vital->blood_pressure ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vital->pulse ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vital->temperature ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vital->oxygen_level ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vital->created_at->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 text-gray-500">
                    No vitals recorded recently.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
