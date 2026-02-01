@extends('layouts.dashboard')

@section('header', 'New IPD Admission')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Admit Patient</h3>
            <div class="mt-2 text-sm text-gray-500">
                <p>Ensure the patient is registered before admission. Select an available bed to proceed.</p>
            </div>

            <form action="{{ route('admin.ipd.admissions.store') }}" method="POST" class="mt-5 space-y-6">
                @csrf
                
                {{-- Patient Selection --}}
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                        <select id="patient_id" name="patient_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700">Attending Doctor</label>
                        <select id="doctor_id" name="doctor_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date & Time</label>
                        <input type="datetime-local" name="admission_date" id="admission_date" value="{{ now()->format('Y-m-d\TH:i') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="sm:col-span-3">
                        <label for="advance_payment" class="block text-sm font-medium text-gray-700">Advance Payment ($)</label>
                        <input type="number" step="0.01" name="advance_payment" id="advance_payment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00">
                    </div>

                    <div class="sm:col-span-6">
                        <label for="reason_for_admission" class="block text-sm font-medium text-gray-700">Reason for Admission</label>
                        <textarea id="reason_for_admission" name="reason_for_admission" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                </div>

                {{-- Ward & Bed Selection (Visual Logic) --}}
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Select Bed</h4>
                    
                    @if($wards->isEmpty())
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No Beds Available</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Please configure Wards and Beds first or discharge patients to free up space.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($wards as $ward)
                                <div class="bg-slate-50 p-4 rounded-md border border-slate-200">
                                    <span class="text-sm font-bold text-slate-700 uppercase tracking-wide">{{ $ward->name }} ({{ ucfirst($ward->type) }})</span>
                                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                        @foreach($ward->beds as $bed)
                                            <label class="cursor-pointer relative">
                                                <input type="radio" name="bed_id" value="{{ $bed->id }}" class="peer sr-only" required>
                                                <div class="p-2 border rounded-md text-center hover:bg-indigo-50 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all bg-white shadow-sm">
                                                    <div class="text-xs font-bold">{{ $bed->bed_number }}</div>
                                                    <div class="text-[10px] opacity-75">${{ $bed->daily_charge }}/day</div>
                                                </div>
                                            </label>
                                        @endforeach
                                        @if($ward->beds->isEmpty())
                                            <span class="text-xs text-slate-400 italic col-span-full">No available beds in this ward.</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="pt-5">
                    <div class="flex justify-end">
                        <a href="{{ route('admin.ipd.admissions.index') }}" class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</a>
                        <button type="submit" class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Admit Patient</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
