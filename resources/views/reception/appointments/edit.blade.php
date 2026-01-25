@extends('layouts.dashboard')

@section('header', 'Edit Appointment')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form action="{{ route('reception.appointments.update', $appointment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                    {{-- Patient (Read Only) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <input type="text" value="{{ $appointment->patient->name }}" disabled class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm">
                    </div>

                    {{-- Doctor --}}
                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700">Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} ({{ $doctor->department->name ?? 'General' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Department --}}
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                        <select name="department_id" id="department_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $appointment->department_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="appointment_date" id="appointment_date" value="{{ $appointment->appointment_date->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Time --}}
                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700">Time</label>
                        <input type="time" name="appointment_time" id="appointment_time" value="{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-2">
                    <a href="{{ route('reception.appointments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
