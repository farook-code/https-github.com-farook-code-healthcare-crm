@extends('layouts.dashboard')

@section('header', 'Admin Dashboard')

@section('content')
    @php
        // Defensive coding: ensure stats exist even if controller fails
        $stats = $stats ?? [
            'users' => \App\Models\User::count(),
            'doctors' => \App\Models\DoctorProfile::count(),
            'departments' => \App\Models\Department::count(),
            'appointments' => \App\Models\Appointment::count(),
        ];
        $recentUsers = $recentUsers ?? collect([]);
        $recentAppointments = $recentAppointments ?? collect([]);
    @endphp

    <div class="space-y-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Users Card -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-3 shadow-lg shadow-indigo-500/30">
                            <!-- Heroicon name: users -->
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.total_users') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['users'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.users.index') }}" class="font-medium text-indigo-700 hover:text-indigo-900">{{ __('messages.view_all') }}</a>
                    </div>
                </div>
            </div>

            <!-- Doctors Card -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 shadow-lg shadow-green-500/30">
                             <!-- Heroicon name: user-group (doctor representation) -->
                             <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                             </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.doctors') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['doctors'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                 <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.doctors.index') }}" class="font-medium text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors">View all</a>
                    </div>
                </div>
            </div>

             <!-- Departments Card -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-3 shadow-lg shadow-purple-500/30">
                             <!-- Heroicon name: office-building -->
                             <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                             </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.departments') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['departments'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                 <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.departments.index') }}" class="font-medium text-purple-700 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 transition-colors">View all</a>
                    </div>
                </div>
            </div>

            <!-- Appointments Card -->
             <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-500 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-3 shadow-lg shadow-orange-500/30">
                             <!-- Heroicon name: calendar -->
                             <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                             </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.appointments') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['appointments'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                 <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                         <a href="{{ route('admin.reports.index') }}" class="font-medium text-orange-700 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300 transition-colors">View details</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Billing Stats Row -->
        @php
            $billingStats = $billingStats ?? [
                'totalRevenue' => 0,
                'pendingAmount' => 0,
                'totalInvoices' => 0,
                'paidInvoices' => 0,
                'pendingInvoices' => 0,
            ];
        @endphp
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mt-6">
            <!-- Total Revenue -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 shadow-lg shadow-green-500/30">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.total_revenue') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($billingStats['totalRevenue'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.invoices.index', ['status' => 'paid']) }}" class="font-medium text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors">View paid invoices &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Pending Amount -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-3 shadow-lg shadow-yellow-500/30">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.pending_amount') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($billingStats['pendingAmount'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.invoices.index', ['status' => 'pending']) }}" class="font-medium text-yellow-700 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 transition-colors">View pending &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-3 shadow-lg shadow-blue-500/30">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.total_invoices') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $billingStats['totalInvoices'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.invoices.index') }}" class="font-medium text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors">View all invoices &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Paid Invoices Count -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-3 shadow-lg shadow-purple-500/30">
                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">{{ __('messages.paid_invoices') }}</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $billingStats['paidInvoices'] }} <span class="text-sm font-normal text-gray-400 dark:text-slate-500">/ {{ $billingStats['totalInvoices'] }}</span></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <span class="font-medium text-purple-700 dark:text-purple-400">{{ $billingStats['pendingInvoices'] }} pending</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue By Category -->
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-8 mb-4">Revenue Breakdown</h3>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
             <!-- OPD Revenue -->
             <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-3 shadow-lg shadow-blue-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">OPD Revenue</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($billingStats['categoryBreakdown']['opd'] ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.invoices.index', ['category' => 'opd']) }}" class="font-medium text-blue-700 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors">View OPD invoices &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Pharmacy Revenue -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-3 shadow-lg shadow-green-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">Pharmacy Revenue</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($billingStats['categoryBreakdown']['pharmacy'] ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.invoices.index', ['category' => 'pharmacy']) }}" class="font-medium text-green-700 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 transition-colors">View Pharmacy invoices &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- IPD Revenue -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
               <div class="p-5">
                   <div class="flex items-center">
                       <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-3 shadow-lg shadow-purple-500/30">
                           <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                       </div>
                       <div class="ml-5 w-0 flex-1">
                           <dl>
                               <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">IPD Revenue</dt>
                               <dd class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($billingStats['categoryBreakdown']['ipd'] ?? 0, 2) }}</dd>
                           </dl>
                       </div>
                   </div>
               </div>
               <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                   <div class="text-sm">
                       <a href="{{ route('admin.invoices.index', ['category' => 'ipd']) }}" class="font-medium text-purple-700 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 transition-colors">View IPD invoices &rarr;</a>
                   </div>
               </div>
           </div>

             <!-- Lab Revenue -->
             <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-slate-100 dark:border-slate-700">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-3 shadow-lg shadow-orange-500/30">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-slate-400 truncate">Lab & Diagnostic</dt>
                                <dd class="text-2xl font-semibold text-gray-900 dark:text-white">${{ number_format($billingStats['categoryBreakdown']['lab'] ?? 0, 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-3">
                    <div class="text-sm">
                        <a href="{{ route('admin.invoices.index', ['category' => 'lab']) }}" class="font-medium text-orange-700 dark:text-orange-400 hover:text-orange-900 dark:hover:text-orange-300 transition-colors">View Lab invoices &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 px-5 py-6 shadow-lg rounded-xl sm:px-6 border border-slate-100 dark:border-slate-700">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">{{ __('messages.revenue_growth') }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                        +12.5%
                    </span>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 px-5 py-6 shadow-lg rounded-xl sm:px-6 border border-slate-100 dark:border-slate-700">
                <div class="mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">{{ __('messages.appointment_status') }}</h3>
                </div>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl border border-slate-100 dark:border-slate-700">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Latest Users</h3>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($recentUsers as $user)
                        <li class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/40 dark:to-indigo-800/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">{{ $user->role->name ?? 'User' }}</div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 dark:text-slate-500">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </li>
                    @empty
                        <li class="px-5 py-6 text-center text-gray-500 dark:text-slate-400">No users found.</li>
                    @endforelse
                </ul>
                <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-2 text-center rounded-b-xl">
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium transition-colors">View all users</a>
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl border border-slate-100 dark:border-slate-700">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.recent_appointments') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                            @forelse($recentAppointments as $appt)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ optional($appt->patient)->name ?? 'Unknown' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400">
                                            w/ {{ optional($appt->doctor)->name ?? 'Doctor' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $appt->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400' : '' }}
                                             {{ $appt->status === 'scheduled' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-400' : '' }}
                                             {{ $appt->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400' : '' }}">
                                            {{ ucfirst($appt->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-slate-400 text-right">
                                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-6 text-center text-gray-500 dark:text-slate-400">No appointments yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 <div class="bg-gray-50 dark:bg-slate-700/50 px-5 py-2 text-center rounded-b-xl">
                    <a href="{{ route('admin.reports.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium transition-colors">View detailed reports</a>
                </div>
            </div>
        </div>

        <!-- Recent Invoices Section -->
        <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl mt-6 border border-slate-100 dark:border-slate-700">
            <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('messages.recent_invoices') ?? 'Recent Invoices' }}</h3>
                <a href="{{ route('admin.invoices.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium transition-colors">View all &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">Invoice</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">Patient</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                        @php
                            $recentInvoices = $recentInvoices ?? collect([]);
                        @endphp
                        @forelse($recentInvoices as $invoice)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                        #INV-{{ str_pad($invoice->id, 5, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $invoice->patient->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($invoice->amount, 2) }}</span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400',
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-400',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-slate-300' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-slate-400">
                                    {{ $invoice->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-gray-500 dark:text-slate-400">No invoices yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-8">{{ __('messages.quick_actions') }}</h3>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
            
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl p-6 flex flex-col items-start hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.user_management') }}</h4>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('messages.manage_users_desc') }}</p>
                <a href="{{ route('admin.users.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-colors">
                    {{ __('messages.manage_users_btn') }}
                </a>
            </div>

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl p-6 flex flex-col items-start hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.system_reports') }}</h4>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('messages.system_reports_desc') }}</p>
                <a href="{{ route('admin.reports.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-colors">
                    {{ __('messages.view_data_btn') }}
                </a>
            </div>

            <!-- Billing Quick Action -->
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl p-6 flex flex-col items-start hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.invoices_billing') ?? 'Billing' }}</h4>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('messages.billing_desc') ?? 'View and manage all invoices and payments.' }}</p>
                <a href="{{ route('admin.invoices.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none transition-colors">
                    {{ __('messages.go_billing') ?? 'View Invoices' }}
                </a>
            </div>
            
             <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl p-6 flex flex-col items-start hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.audit_logs') }}</h4>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('messages.audit_logs_desc') }}</p>
                <a href="{{ route('admin.logs.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none transition-colors">
                    {{ __('messages.check_logs') }}
                </a>
            </div>

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-lg rounded-xl p-6 flex flex-col items-start hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 dark:border-slate-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-white mb-2">{{ __('messages.messages') }}</h4>
                <p class="text-sm text-gray-500 dark:text-slate-400 mb-4">{{ __('messages.check_messages_desc') }}</p>
                <a href="{{ route('chat.index') }}" class="mt-auto inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition-colors">
                    {{ __('messages.open_chat_btn') }}
                </a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart (Line)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($revenueData) !!},
                    borderColor: '#4f46e5', // Indigo 600
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        // Data from Controller: [completed: X, scheduled: Y, cancelled: Z]
        const statusData = {!! json_encode($appointmentStatus) !!};
        
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Scheduled', 'Cancelled'],
                datasets: [{
                    data: [statusData.completed, statusData.scheduled, statusData.cancelled],
                    backgroundColor: [
                        '#10b981', // Emerald 500
                        '#3b82f6', // Blue 500
                        '#ef4444'  // Red 500
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                }
            }
        });
    });
</script>
@endpush
