<x-dashboard-layout>
    @section('header', __('messages.overview'))

    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="p-6 text-slate-900 dark:text-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">{{ __('messages.welcome', ['name' => auth()->user()->name]) }}</h3>
                    <p class="text-slate-500 mt-1">{{ __('messages.happening') }}</p>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ __('messages.portal', ['role' => ucfirst(auth()->user()->role->slug ?? 'User')]) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Role Specific Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php $role = optional(auth()->user()->role)->slug; @endphp

            @if($role === 'admin')
                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                    <div class="h-10 w-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h4 class="font-bold text-lg mb-1">User Management</h4>
                    <p class="text-sm text-slate-500 mb-4">Manage doctors, staff, and system access.</p>
                    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 font-medium hover:text-indigo-500">Manage Users &rarr;</a>
                </div>

                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                    <div class="h-10 w-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="font-bold text-lg mb-1">System Reports</h4>
                    <p class="text-sm text-slate-500 mb-4">View clinic performance and analytics.</p>
                    <a href="{{ route('admin.reports.index') }}" class="text-green-600 font-medium hover:text-green-500">View Data &rarr;</a>
                </div>
            @endif

            @if($role === 'doctor')
                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                    <div class="h-10 w-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <h4 class="font-bold text-lg mb-1">Today's Appointments</h4>
                    <p class="text-sm text-slate-500 mb-4">Check your schedule and patient list.</p>
                    <a href="{{ route('doctor.appointments') }}" class="text-blue-600 font-medium hover:text-blue-500">Go to Calendar &rarr;</a>
                </div>
            @endif

            @if($role === 'reception')
                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                    <div class="h-10 w-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                    <h4 class="font-bold text-lg mb-1">New Patient</h4>
                    <p class="text-sm text-slate-500 mb-4">Register a new patient into the system.</p>
                    <a href="{{ route('reception.patients.create') }}" class="text-purple-600 font-medium hover:text-purple-500">Register Patient &rarr;</a>
                </div>
            @endif
            
            <!-- Messages Widget (Common) -->
            <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                <div class="h-10 w-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <h4 class="font-bold text-lg mb-1">Messages</h4>
                <p class="text-sm text-slate-500 mb-4">Check internal communications.</p>
                <a href="{{ route('chat.index') }}" class="text-orange-600 font-medium hover:text-orange-500">Open Chat &rarr;</a>
            </div>
        </div>
    </div>
</x-dashboard-layout>
