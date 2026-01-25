@extends('layouts.dashboard')

@section('header', 'Analytics & Reports')

@section('content')
<div class="max-w-7xl mx-auto p-5 space-y-12">
    
    {{-- Filter Control Panel --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 mb-12">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-slate-800 flex items-center">
                <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter Reports
            </h3>
            <span class="text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded">
                Selecting range will update all metrics below
            </span>
        </div>
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col md:flex-row md:items-end gap-5">
            <div class="flex flex-row gap-4 flex-grow">
                <div class="w-full md:w-auto">
                    <label for="start_date" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">From</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="w-full md:w-auto">
                    <label for="end_date" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">To</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>

            <div class="flex items-center gap-3 mt-4 md:mt-0">
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-sm font-semibold transition-colors shadow-sm">
                    Apply Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="text-slate-600 hover:text-slate-900 text-sm font-medium px-4 py-2 hover:bg-slate-50 rounded-lg transition-colors">
                    Reset
                </a>
            </div>
            
            <div class="ml-auto mt-4 md:mt-0">
                <button type="button" onclick="window.print()" class="w-full md:w-auto bg-emerald-600 text-white px-5 py-2.5 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 text-sm font-semibold flex items-center justify-center shadow-sm transition-colors cursor-pointer group">
                    <svg class="h-4 w-4 mr-2 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Download PDF
                </button>
            </div>
        </form>
    </div>
    
    {{-- Key Metrics Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
        <!-- Card 1 -->
        <div class="bg-gradient-to-br from-white to-slate-50 overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Appointments</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalAppointments }}</p>
                    </div>
                    <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                    <span class="text-green-600 font-semibold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        +12%
                    </span>
                    <span class="text-slate-400 ml-2">from last month</span>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-gradient-to-br from-white to-slate-50 overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Active Patients</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalPatients }}</p>
                    </div>
                    <div class="h-12 w-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                     <span class="text-green-600 font-semibold flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        +8%
                    </span>
                    <span class="text-slate-400 ml-2">new registrations</span>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-gradient-to-br from-white to-slate-50 overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Revenue (Paid)</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: 75%"></div>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-gradient-to-br from-white to-slate-50 overflow-hidden shadow-sm rounded-xl border border-slate-200 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pending Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">${{ number_format($pendingRevenue, 2) }}</p>
                    </div>
                    <div class="h-12 w-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                 <div class="mt-4 flex items-center text-xs">
                     <span class="text-amber-600 font-semibold">Action Required</span>
                    <span class="text-slate-400 ml-2">unpaid invoices</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts / Details Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        
        {{-- Status Breakdown (Chart Style) --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-5 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                Appointment Status
            </h3>
            <div class="space-y-6 flex-grow">
                @foreach($appointmentsByStatus as $status => $count)
                    <div>
                        <div class="flex justify-between text-sm font-medium mb-1">
                            <span class="text-slate-600">{{ ucfirst($status) }}</span>
                            <span class="text-slate-900 font-bold">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3">
                            @php
                                $color = match(strtolower($status)) {
                                    'completed', 'paid' => 'bg-emerald-500', 
                                    'scheduled', 'pending' => 'bg-indigo-500', 
                                    'cancelled' => 'bg-rose-500',
                                    default => 'bg-indigo-300'
                                };
                            @endphp
                            <div class="{{ $color }} h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ ($count / max($totalAppointments, 1)) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
                 @if(count($appointmentsByStatus) == 0)
                    <div class="flex flex-col items-center justify-center h-40 text-slate-400">
                        <p>No data available</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity (Feed Style) --}}
        <div class="bg-white shadow-sm border border-slate-200 rounded-xl p-5">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Recent Bookings
                </h3>
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All</a>
            </div>
            
            <div class="overflow-hidden">
                <ul class="divide-y divide-slate-100">
                    @forelse($recentAppointments as $appt)
                        <li class="py-4 hover:bg-slate-50 transition-colors rounded-lg px-2 -mx-2">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center justify-center h-10 w-10 socket rounded-full bg-indigo-100 text-indigo-500 font-bold">
                                        {{ substr($appt->patient->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 truncate">
                                        {{ $appt->patient->name }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">
                                        Dr. {{ $appt->doctor->name }} &bull; {{ $appt->department->name ?? 'General' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-slate-700">{{ $appt->appointment_date->format('M d') }}</p>
                                    <p class="text-xs text-slate-500">{{ $appt->appointment_time }}</p>
                                </div>
                                <div>
                                    @if($appt->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Completed</span>
                                    @elseif($appt->status === 'cancelled')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Cancelled</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Scheduled</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="py-10 text-center text-slate-500 italic">No recent appointments found for this period.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
