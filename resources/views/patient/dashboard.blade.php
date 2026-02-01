@extends('layouts.dashboard')

@section('header', __('messages.my_health_dashboard'))

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6 mb-20 md:mb-0">
    
    {{-- Welcome & Quick Actions --}}
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ __('messages.welcome_user', ['name' => auth()->user()->name]) }}</h1>
            <p class="text-sm text-gray-500 dark:text-slate-400">{{ __('messages.health_overview_desc') }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-3">
             <a href="{{ route('chat.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                {{ __('messages.messages_title') }}
            </a>
             <a href="{{ route('patient.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                {{ __('messages.book_appointment_btn') }}
            </a>
        </div>
    </div>

    {{-- Services / Quick Access --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
        
        <!-- OPD -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 flex flex-col hover:shadow-md hover:border-blue-300 dark:hover:border-blue-600 transition-all">
            <div class="p-5 flex items-center flex-1">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                    <span class="text-2xl">📅</span>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">OPD</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Appointments</h3>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3 border-t border-gray-100 dark:border-slate-700 rounded-b-xl">
                 <a href="{{ route('patient.appointments.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 uppercase flex items-center transition-colors">
                    Book Now <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- IPD -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 flex flex-col hover:shadow-md hover:border-emerald-300 dark:hover:border-emerald-600 transition-all">
            <div class="p-5 flex items-center flex-1">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/40 dark:to-emerald-800/40 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <span class="text-2xl">🛏️</span>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">IPD</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Admissions</h3>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3 border-t border-gray-100 dark:border-slate-700 rounded-b-xl">
                 <a href="{{ route('patient.admissions.index') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 uppercase flex items-center transition-colors">
                    View History <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Pharmacy -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 flex flex-col hover:shadow-md hover:border-purple-300 dark:hover:border-purple-600 transition-all">
            <div class="p-5 flex items-center flex-1">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/40 dark:to-purple-800/40 flex items-center justify-center text-purple-600 dark:text-purple-400 shrink-0">
                    <span class="text-2xl">💊</span>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Pharmacy</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Prescriptions</h3>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3 border-t border-gray-100 dark:border-slate-700 rounded-b-xl">
                 <a href="{{ route('patient.prescriptions.index') }}" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 uppercase flex items-center transition-colors">
                    Request Refill <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Pathology -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 flex flex-col hover:shadow-md hover:border-orange-300 dark:hover:border-orange-600 transition-all">
            <div class="p-5 flex items-center flex-1">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/40 dark:to-orange-800/40 flex items-center justify-center text-orange-600 dark:text-orange-400 shrink-0">
                    <span class="text-2xl">🔬</span>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Pathology</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">Lab Reports</h3>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3 border-t border-gray-100 dark:border-slate-700 rounded-b-xl">
                 <a href="{{ route('lab-reports.patient-records', auth()->user()->patient->id) }}" class="text-xs font-bold text-orange-600 dark:text-orange-400 hover:text-orange-800 dark:hover:text-orange-300 uppercase flex items-center transition-colors">
                    View Reports <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Insurance -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 flex flex-col hover:shadow-md hover:border-rose-300 dark:hover:border-rose-600 transition-all">
            <div class="p-5 flex items-center flex-1">
                <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-rose-100 to-rose-200 dark:from-rose-900/40 dark:to-rose-800/40 flex items-center justify-center text-rose-600 dark:text-rose-400 shrink-0">
                    <span class="text-2xl">🛡️</span>
                </div>
                <div class="ml-4">
                    <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Insurance</p>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">My Claims</h3>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3 border-t border-gray-100 dark:border-slate-700 rounded-b-xl">
                 <a href="#insurance-claims" class="text-xs font-bold text-rose-600 dark:text-rose-400 hover:text-rose-800 dark:hover:text-rose-300 uppercase flex items-center transition-colors">
                    Track Status <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

    </div>

    {{-- Billing & Expenses Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
         <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 dark:from-indigo-700 dark:to-indigo-800 rounded-lg shadow-md p-5 text-white flex justify-between items-center relative overflow-hidden group hover:shadow-xl transition-shadow">
             <div class="relative z-10">
                 <p class="text-indigo-100 dark:text-indigo-200 text-sm font-medium mb-1">Total Outstanding</p>
                 <h2 class="text-3xl font-bold">${{ number_format($pendingAmount, 2) }}</h2>
                 <p class="text-xs text-indigo-200 dark:text-indigo-300 mt-2">{{ isset($invoices) ? $invoices->where('status', '!=', 'paid')->count() : 0 }} Unpaid Invoices</p>
             </div>
             <div class="h-24 w-24 bg-white opacity-10 dark:opacity-20 rounded-full flex items-center justify-center absolute -right-6 -bottom-6">
                 <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
             </div>
         </div>
          <div class="bg-white dark:bg-slate-800 dark:border dark:border-slate-700 rounded-lg shadow-md p-5 border border-gray-200 relative overflow-hidden group hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
             <div class="absolute top-4 right-4 z-20">
                 <a href="{{ route('patient.statement') }}" class="text-gray-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition p-1" title="Print Insurance Financing Statement">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                 </a>
             </div>
             <p class="text-gray-500 dark:text-slate-400 text-sm font-medium mb-1">Total Paid</p>
             <h2 class="text-3xl font-bold text-gray-900 dark:text-slate-100">${{ number_format($totalSpent, 2) }}</h2>
             <p class="text-xs text-green-600 dark:text-green-400 mt-2 font-medium flex items-center">
                 <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                 Lifetime Medical Expenses
             </p>
         </div>
          <div class="bg-white dark:bg-slate-800 dark:border dark:border-slate-700 rounded-lg shadow-md p-5 border border-gray-200 relative overflow-hidden group hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
             <p class="text-gray-500 dark:text-slate-400 text-sm font-medium mb-1">Latest Invoice</p>
             @if(isset($invoices) && $invoices->first())
                <h2 class="text-3xl font-bold text-gray-900 dark:text-slate-100">${{ number_format($invoices->first()->amount, 2) }}</h2>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-400 dark:text-slate-500">{{ $invoices->first()->issued_at->format('M d, Y') }}</span>
                     <span class="px-2 py-0.5 rounded text-xs font-bold {{ $invoices->first()->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400' }}">
                         {{ ucfirst($invoices->first()->status) }}
                     </span>
                </div>
                {{-- View Invoice Button Overlay --}}
                <a href="{{ route('reception.invoices.print', $invoices->first()) }}" class="absolute inset-0 z-10" title="View Invoice"></a>
             @else
                <h2 class="text-xl font-bold text-gray-400 dark:text-slate-500 mt-2">No Invoices</h2>
             @endif
         </div>
    </div>

    {{-- Vitals Cards --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Heart Rate --}}
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500 dark:border-red-600 hover:shadow-xl transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/40 dark:to-red-800/40 flex items-center justify-center">
                            <span class="text-2xl">💓</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.heart_rate') }}</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-slate-100">
                                    {{ $latestVitals->heart_rate ?? '--' }} <span class="text-xs text-gray-500 dark:text-slate-500">bpm</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Blood Pressure --}}
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500 dark:border-blue-600 hover:shadow-xl transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 flex items-center justify-center">
                            <span class="text-2xl">🩸</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.blood_pressure') }}</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-slate-100">
                                    {{ $latestVitals->blood_pressure_systolic ?? '--' }}/{{ $latestVitals->blood_pressure_diastolic ?? '--' }}
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Weight --}}
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500 dark:border-green-600 hover:shadow-xl transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/40 dark:to-green-800/40 flex items-center justify-center">
                            <span class="text-2xl">⚖️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.weight') }}</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-slate-100">
                                    {{ $latestVitals->weight ?? '--' }} <span class="text-xs text-gray-500 dark:text-slate-500">kg</span>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Temperature --}}
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500 dark:border-yellow-600 hover:shadow-xl transition-all">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900/40 dark:to-yellow-800/40 flex items-center justify-center">
                            <span class="text-2xl">🌡️</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.temperature') }}</dt>
                            <dd>
                                <div class="text-lg font-medium text-gray-900 dark:text-slate-100">
                                    {{ $latestVitals->temperature ?? '--' }} <span class="text-xs text-gray-500 dark:text-slate-500">°C</span>
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
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-gray-200 dark:border-slate-700">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-100">{{ __('messages.recent_appointments_title') }}</h3>
                <a href="{{ route('patient.appointments.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">{{ __('messages.view_all') }}</a>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                @forelse($appointments as $apt)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 truncate">
                                {{ $apt->doctor->name ?? __('messages.unassigned') }}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $apt->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400' : '' }}
                                    {{ $apt->status === 'scheduled' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-400' : '' }}
                                    {{ $apt->status === 'completed' ? 'bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-slate-300' : '' }}">
                                    {{ ucfirst($apt->status) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500 dark:text-slate-400">
                                    📅 {{ $apt->appointment_date ? $apt->appointment_date->format('M d, Y') : '-' }} • 🕒 {{ $apt->appointment_time }}
                                </p>
                            </div>
                             <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-slate-400 sm:mt-0">
                                <a href="{{ route('patient.appointments.show', $apt) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">{{ __('messages.view_details_arrow') }}</a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400 text-center">{{ __('messages.no_appointments_found') }}</li>
                @endforelse
            </ul>
        </div>

        {{-- Medical & Prescriptions Overview --}}
        <div class="space-y-6">

             {{-- Insurance Claims --}}
            <div id="insurance-claims" class="bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-gray-200 dark:border-slate-700">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-100">Insurance Claims</h3>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($insuranceClaims as $claim)
                         <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-slate-100 border-b border-dashed border-gray-300 dark:border-slate-600 inline-block">{{ $claim->claim_number }}</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">{{ $claim->provider_name }} • Inv #{{ $claim->invoice_id }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-bold rounded {{ $claim->status == 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400' : ($claim->status == 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-400') }}">
                                        {{ ucfirst($claim->status) }}
                                    </span>
                                    <p class="text-sm font-bold text-gray-700 dark:text-slate-300 mt-1">${{ number_format($claim->amount_claimed, 2) }}</p>
                                </div>
                            </div>
                        </li>
                    @empty
                         <li class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400 text-center">No active insurance claims.</li>
                    @endforelse
                </ul>
            </div>
            
            {{-- Prescriptions --}}
            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-gray-200 dark:border-slate-700">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-100">{{ __('messages.recent_prescriptions_title') }}</h3>
                    <a href="{{ route('patient.prescriptions.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($prescriptions as $p)
                        <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $p->medicine_name }}</span>
                                <span class="text-sm text-gray-500 dark:text-slate-400">{{ $p->dosage }}</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Dr. {{ $p->diagnosis->doctor->name ?? 'Unknown' }} • {{ $p->created_at->format('M d') }}</p>
                        </li>
                    @empty
                         <li class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400 text-center">{{ __('messages.no_active_prescriptions') }}</li>
                    @endforelse
                </ul>
            </div>

            {{-- Lab Reports --}}
             <div class="bg-white dark:bg-slate-800 shadow-lg rounded-lg border border-gray-200 dark:border-slate-700">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-slate-700 flex justify-between items-center bg-gray-50 dark:bg-slate-700/50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-slate-100">{{ __('messages.lab_reports_title') }}</h3>
                    {{-- Access records using the public route with this user --}}
                     <a href="{{ route('lab-reports.index', auth()->user()->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">{{ __('messages.view_all') }}</a>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($labReports as $report)
                         <li class="px-4 py-4 sm:px-6 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900 dark:text-slate-100">{{ $report->title }}</span>
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-slate-300">{{ strtoupper($report->file_type) }}</span>
                            </div>
                            <a href="{{ route('lab-reports.download', $report) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm font-medium">{{ __('messages.download_btn') }}</a>
                        </li>
                    @empty
                         <li class="px-4 py-4 text-sm text-gray-500 dark:text-slate-400 text-center">{{ __('messages.no_records_found') }}</li>
                    @endforelse
                </ul>
            </div>
            
        </div>
    </div>
</div>
@endsection
