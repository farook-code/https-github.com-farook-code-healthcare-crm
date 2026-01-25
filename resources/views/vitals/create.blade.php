@extends('layouts.dashboard')

@section('header', 'Record Vitals')

@section('content')
<div class="max-w-2xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Record Patient Vitals
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                For Appointment with {{ $appointment->doctor->name }} on {{ $appointment->appointment_date }}
            </p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('vitals.store', $appointment->id) }}">
                @csrf
        
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700">Height (cm)</label>
                        <div class="mt-1">
                            <input type="text" name="height" id="height" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="175">
                        </div>
                    </div>
        
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                        <div class="mt-1">
                            <input type="text" name="weight" id="weight" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="70">
                        </div>
                    </div>
        
                    <div>
                        <label for="blood_pressure" class="block text-sm font-medium text-gray-700">Blood Pressure</label>
                        <div class="mt-1">
                            <input type="text" name="blood_pressure" id="blood_pressure" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="120/80">
                        </div>
                    </div>
        
                    <div>
                        <label for="pulse" class="block text-sm font-medium text-gray-700">Pulse (bpm)</label>
                        <div class="mt-1">
                            <input type="text" name="pulse" id="pulse" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="72">
                        </div>
                    </div>
        
                    <div>
                        <label for="temperature" class="block text-sm font-medium text-gray-700">Temperature (°C)</label>
                        <div class="mt-1">
                            <input type="text" name="temperature" id="temperature" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="36.6">
                        </div>
                    </div>
        
                    <div>
                        <label for="oxygen_level" class="block text-sm font-medium text-gray-700">Oxygen Level (SpO2)</label>
                        <div class="mt-1">
                            <input type="text" name="oxygen_level" id="oxygen_level" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="98%">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Care Notes / Observations</label>
                        <div class="mt-1">
                            <textarea id="notes" name="notes" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter any specific observations or care notes..."></textarea>
                        </div>
                    </div>
                </div>
        
                <div class="mt-6">
                    <a href="{{ route('doctor.appointments.show', $appointment->id) }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2 sm:w-auto">
                        Cancel
                    </a>
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto">
                        Save Vitals
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
