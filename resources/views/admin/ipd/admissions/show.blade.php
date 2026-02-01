@extends('layouts.dashboard')

@section('header', 'Admission Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    {{-- Top Action Bar --}}
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li><a href="{{ route('admin.ipd.admissions.index') }}" class="text-gray-400 hover:text-gray-500"><svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></a></li>
                    <li><span class="text-sm font-medium text-gray-500">Back to Admissions</span></li>
                </ol>
            </nav>
            <h2 class="mt-2 text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Admission #{{ $admission->id }}
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
             @if($admission->status === 'admitted')
                <button onclick="openDischargeModal()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    Process Discharge
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Patient & Admission Info --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Patient Card --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                     <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Patient Information
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $admission->status === 'admitted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($admission->status) }}
                    </span>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-semibold">{{ $admission->patient->name }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Patient ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $admission->patient->patient_code ?? $admission->patient_id }}</dd>
                        </div>
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Contact</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $admission->patient->phone ?? 'N/A' }}</dd>
                        </div>
                         <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Attending Doctor</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 text-indigo-600 font-medium">Dr. {{ $admission->doctor->name }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Clinical Details --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                 <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                     <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Clinical Details
                    </h3>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Reason for Admission</h4>
                    <p class="text-gray-900 bg-gray-50 p-3 rounded-md border border-gray-200 text-sm">
                        {{ $admission->reason_for_admission ?? 'No reason recorded.' }}
                    </p>

                    @if($admission->status === 'discharged')
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mt-6 mb-2">Discharge Summary</h4>
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                             <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="block text-xs text-gray-500">Admitted Date</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $admission->admission_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-500">Discharge Date</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $admission->discharge_date ? $admission->discharge_date->format('M d, Y') : '-' }}</span>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                 <h5 class="text-xs font-bold text-gray-700 uppercase mb-1">Doctor's Notes</h5>
                                 <p class="text-sm text-gray-600">{{ $admission->discharge_notes ?? 'No notes available.' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right Column: Bed & Financials --}}
        <div class="space-y-6">
            
            {{-- Bed Assignment Widget --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-4 py-5 bg-indigo-600 text-white flex justify-between items-start">
                    <div>
                        <p class="text-indigo-100 text-sm">Assigned Bed</p>
                        @if($admission->bed)
                            <h3 class="text-3xl font-bold mt-1">{{ $admission->bed->bed_number }}</h3>
                            <p class="text-indigo-200 text-sm">{{ $admission->bed->ward->name }}</p>
                        @else
                             <h3 class="text-xl font-bold mt-1">Released</h3>
                             <p class="text-indigo-200 text-sm">No active bed</p>
                        @endif
                    </div>
                    <svg class="h-10 w-10 text-indigo-400 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>
                </div>
                <div class="bg-gray-50 px-4 py-4 border-t border-gray-200">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">Daily Charge</span>
                        <span class="font-medium text-gray-900">${{ number_format($admission->bed->daily_charge ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                 <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Billing Overview</h3>
                </div>
                <div class="px-4 py-5 sm:p-6 space-y-4">
                     <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Advance Paid</span>
                        <span class="text-sm font-bold text-green-600">${{ number_format($admission->advance_payment, 2) }}</span>
                    </div>
                     <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                        <span class="text-sm text-gray-500">Estimated Total</span>
                         @if($admission->status === 'discharged')
                            <span class="text-lg font-bold text-gray-900">${{ number_format($admission->total_estimate, 2) }}</span>
                         @else
                            <span class="text-sm text-gray-400 italic">Calculated at discharge</span>
                         @endif
                    </div>
                    @if($admission->status === 'discharged')
                        <div class="rounded-md bg-yellow-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Payment Status</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                    <p>Ensure final invoice #{{ $admission->id }} is generated and cleared.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

{{-- Discharge Modal --}}
@if($admission->status === 'admitted')
<div id="dischargeModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('admin.ipd.admissions.discharge', $admission->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Discharge Patient</h3>
                            <div class="mt-2 text-sm text-gray-500">
                                <p>Calculate final charges and confirm discharge.</p>
                            </div>

                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Discharge Date</label>
                                    <input type="datetime-local" name="discharge_date" id="discharge_date" onchange="calculateBill()" value="{{ now()->format('Y-m-d\TH:i') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                     <div>
                                        <label class="block text-sm font-medium text-gray-700">Bed Charge/Day</label>
                                        <input type="text" value="{{ number_format($admission->bed->daily_charge ?? 0, 2) }}" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Days Stayed</label>
                                        <input type="number" id="days_stayed" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm sm:text-sm">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Advance Paid</label>
                                    <input type="number" value="{{ $admission->advance_payment }}" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm sm:text-sm">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Bill Amount</label>
                                    <input type="number" name="total_estimate" id="total_estimate" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-lg font-bold text-indigo-600">
                                    <p class="text-xs text-gray-400 mt-1">Includes Bed Charges - Advance. Add services manually if needed.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Discharge Notes</label>
                                    <textarea name="discharge_notes" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm">Routine discharge.</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">Confirm Discharge</button>
                    <button type="button" onclick="closeDischargeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('dischargeModal');
    const dailyRate = {{ $admission->bed->daily_charge ?? 0 }};
    const admissionDate = new Date("{{ $admission->admission_date->format('Y-m-d H:i:s') }}");
    const advance = {{ $admission->advance_payment }};

    function openDischargeModal() {
        modal.classList.remove('hidden');
        calculateBill();
    }

    function closeDischargeModal() {
        modal.classList.add('hidden');
    }

    function calculateBill() {
        const dischargeInput = document.getElementById('discharge_date').value;
        const dischargeDate = new Date(dischargeInput);

        // Calculate Diff Days (Ceil)
        const diffTime = Math.abs(dischargeDate - admissionDate);
        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
        if(diffDays < 1) diffDays = 1; // Minimum 1 day charge
        
        if (isNaN(diffDays)) diffDays = 1;

        document.getElementById('days_stayed').value = diffDays;

        const totalBedCharge = diffDays * dailyRate;
        const finalBill = totalBedCharge - advance; 
        
        // Show Net Payable
        document.getElementById('total_estimate').value = (finalBill).toFixed(2);
    }
</script>
@endif
