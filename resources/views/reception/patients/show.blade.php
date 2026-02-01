@extends('layouts.dashboard')

@section('header', 'Patient Profile')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8" x-data="{ activeTab: 'overview' }">
    
    {{-- Header / Basic Info --}}
    <div class="bg-white shadow rounded-lg mb-6 overflow-hidden">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50 border-b border-gray-200">
            <div class="flex items-center">
                <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 text-2xl font-bold">
                    {{ substr($patient->name, 0, 1) }}
                </div>
                <div class="ml-4">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">{{ $patient->name }}</h3>
                    <p class="text-sm text-gray-500">Code: {{ $patient->patient_code }} | DOB: {{ $patient->dob ? $patient->dob->format('Y-m-d') : 'N/A' }} | {{ ucfirst($patient->gender) }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('reception.appointments.create', ['patient_id' => $patient->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Book Appointment
                </a>
                <a href="{{ route('reception.patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Edit Profile
                </a>
            </div>
        </div>
        
        {{-- Navigation Tabs --}}
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Overview
                </button>
                <button @click="activeTab = 'financials'" :class="activeTab === 'financials' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Financials & Billing
                </button>
                <button @click="activeTab = 'medical'" :class="activeTab === 'medical' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm">
                    Medical History
                </button>
            </nav>
        </div>
    </div>

    {{-- Financials Tab --}}
    <div x-show="activeTab === 'financials'" class="space-y-6">
        
        {{-- Spending Breakdown Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Spending -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Spending</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900">${{ number_format($billingStats['total'], 2) }}</dd>
                </div>
            </div>
            
            <!-- OPD -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-500">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">OPD (Consultations)</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($billingStats['opd'], 2) }}</dd>
                </div>
            </div>

            <!-- Pharmacy -->
            <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
                <div class="px-4 py-5 sm:p-6">
                    <dt class="text-sm font-medium text-gray-500 truncate">Pharmacy & Medicines</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">${{ number_format($billingStats['pharmacy'], 2) }}</dd>
                </div>
            </div>

             <!-- IPD & Lab -->
             <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-purple-500">
                <div class="px-4 py-5 sm:p-6 flex flex-col justify-between h-full">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 truncate">In-Patient (IPD)</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($billingStats['ipd'], 2) }}</dd>
                    </div>
                     <div class="mt-2 pt-2 border-t border-gray-100">
                        <dt class="text-xs font-medium text-gray-500 truncate">Lab Tests</dt>
                        <dd class="mt-1 text-md font-semibold text-gray-700">${{ number_format($billingStats['lab'], 2) }}</dd>
                    </div>
                </div>
            </div>
        </div>

        {{-- Latest Invoices Table --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Billing History</h3>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patient->invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($invoice->category == 'opd') <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">OPD</span>
                                    @elseif($invoice->category == 'pharmacy') <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Pharmacy</span>
                                    @elseif($invoice->category == 'ipd') <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">IPD</span>
                                    @else <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">{{ ucfirst($invoice->category) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($invoice->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                     <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No invoices found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Overview Tab --}}
    <div x-show="activeTab === 'overview'" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Patient Information</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Full name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $patient->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $patient->email }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $patient->phone ?? 'N/A' }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $patient->address ?? 'N/A' }}</dd>
                </div>
                 <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Insurance Provider</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $patient->insurance_provider ?? 'None' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Medical Info Tab (Placeholder) --}}
    <div x-show="activeTab === 'medical'" class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Past Visits & Records</h3>
        <ul class="space-y-4">
             @forelse($patient->appointments as $appt)
                <li class="border rounded-md p-4 hover:bg-gray-50">
                    <div class="flex justify-between">
                        <span class="font-bold">Consultation - {{ $appt->start_time->format('M d, Y') }}</span>
                        <span class="text-sm text-gray-500">Dr. {{ $appt->doctor->name }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Status: {{ ucfirst($appt->status) }}</p>
                </li>
             @empty
                <p class="text-gray-500 italic">No medical history recorded.</p>
             @endforelse
        </ul>
    </div>

</div>
@endsection
