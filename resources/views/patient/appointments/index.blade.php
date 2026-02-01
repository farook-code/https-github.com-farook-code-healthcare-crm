@extends('layouts.dashboard')
@section('header', 'My Appointments')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 mb-20 md:mb-0">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Appointments</h2>
            <a href="{{ route('patient.dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium mt-1 inline-block">&larr; Back to Dashboard</a>
        </div>
        <div class="mt-4 sm:mt-0">
             <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Book New Appointment
            </a>
        </div>
    </div>

    @if($appointments->count() > 0)
    <div class="grid gap-6 lg:grid-cols-2">
        @foreach($appointments as $appointment)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow p-6 relative">
                 <div class="flex justify-between items-start">
                     <div class="flex items-center">
                         <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xl font-bold">
                             {{ substr($appointment->doctor->name ?? 'D', 0, 1) }}
                         </div>
                         <div class="ml-4">
                             <h3 class="text-lg font-medium text-gray-900">{{ $appointment->doctor->name ?? 'Unassigned' }}</h3>
                             <p class="text-sm text-gray-500">{{ $appointment->doctor->doctorProfile->specialization ?? 'General Physician' }}</p>
                         </div>
                     </div>
                     <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $appointment->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $appointment->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                     ">
                        {{ ucfirst($appointment->status) }}
                     </span>
                 </div>
                 
                 <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-600">
                     <div class="flex items-center">
                         <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                         {{ $appointment->appointment_date ? $appointment->appointment_date->format('M d, Y') : '-' }}
                     </div>
                     <div class="flex items-center">
                         <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                         {{ $appointment->appointment_time }}
                     </div>
                 </div>

                 <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-4">
                     <a href="{{ route('patient.appointments.show', $appointment->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">View Details &rarr;</a>
                     
                     @if(in_array($appointment->status, ['confirmed', 'in_progress']))
                        <a href="{{ route('telemedicine.join', $appointment) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                            📹 Join Call
                        </a>
                     @endif
                 </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $appointments->links() }}
    </div>

    @else
        <div class="bg-white p-12 text-center rounded-lg shadow-sm border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments</h3>
            <p class="mt-1 text-sm text-gray-500">Book your first appointment today.</p>
            <div class="mt-6">
                <a href="{{ route('patient.appointments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Book Appointment
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
