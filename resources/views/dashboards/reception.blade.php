@extends('layouts.dashboard')

@section('header', 'Reception Dashboard')

@section('content')
<div class="space-y-6">


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Appointments -->
        <a href="{{ route('reception.appointments.index') }}" class="block group">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3 group-hover:bg-blue-600 transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Appointments</h3>
                        </div>
                        <p class="text-sm text-gray-500">Create & manage patient appointments, view schedules, and handle check-ins.</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-blue-600 group-hover:text-blue-500">Manage Schedule &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Patients -->
        <a href="{{ route('reception.patients.create') }}" class="block group">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3 group-hover:bg-green-600 transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Registration</h3>
                        </div>
                        <p class="text-sm text-gray-500">Register new patients, update patient details, and create patient accounts.</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-green-600 group-hover:text-green-500">Register Patient &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Invoice/Billing -->
        <a href="{{ route('reception.appointments.index') }}" class="block group">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3 group-hover:bg-yellow-600 transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Invoices & Billing</h3>
                        </div>
                        <p class="text-sm text-gray-500">Generate invoices, process payments, and print receipts directly from the appointment list.</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-yellow-600 group-hover:text-yellow-500">Go to Billing &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

    </div>
</div>
@endsection
