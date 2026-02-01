@extends('layouts.dashboard')

@section('header', 'Reception Dashboard')

@section('content')
<div class="space-y-6">


    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <!--- Appointments -->
        <a href="{{ route('reception.appointments.index') }}" class="block group">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-3 shadow-lg shadow-blue-500/30 group-hover:shadow-blue-600/40 transition-all">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.appointments') }}</h3>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('messages.create_manage_appt') }}</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400 group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">{{ __('messages.manage_schedule') }} &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Patients -->
        <a href="{{ route('reception.patients.create') }}" class="block group">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 shadow-lg shadow-green-500/30 group-hover:shadow-green-600/40 transition-all">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.registration') }}</h3>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('messages.register_new_patient_desc') }}</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-green-600 dark:text-green-400 group-hover:text-green-700 dark:group-hover:text-green-300 transition-colors">{{ __('messages.register_patient') }} &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Invoice/Billing -->
        <a href="{{ route('reception.invoices.index') }}" class="block group">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-3 shadow-lg shadow-yellow-500/30 group-hover:shadow-yellow-600/40 transition-all">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.invoices_billing') }}</h3>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('messages.invoices_desc') }}</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400 group-hover:text-yellow-700 dark:group-hover:text-yellow-300 transition-colors">{{ __('messages.go_billing') }} &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- Kiosk Mode -->
        <a href="{{ route('reception.kiosk.index') }}" target="_blank" class="block group">
             <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-3 shadow-lg shadow-purple-500/30 group-hover:shadow-purple-600/40 transition-all">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">Check-in Kiosk</h3>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Self-service patient check-in mode (Tablet).</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-purple-600 dark:text-purple-400 group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors" >Launch Kiosk &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

        <!-- TV Queue Display -->
        <a href="{{ route('reception.queue.display') }}" target="_blank" class="block group">
             <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-gradient-to-br from-indigo-900 to-indigo-800 rounded-lg p-3 shadow-lg shadow-indigo-900/30 group-hover:shadow-indigo-800/40 transition-all">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900 dark:text-white">TV Queue Display</h3>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Public waiting room display screen.</p>
                    </div>
                    <div class="mt-4">
                        <span class="text-sm font-medium text-indigo-900 dark:text-indigo-400 group-hover:text-indigo-800 dark:group-hover:text-indigo-300 transition-colors">Open Display &rarr;</span>
                    </div>
                </div>
            </div>
        </a>

    </div>
</div>
@endsection
