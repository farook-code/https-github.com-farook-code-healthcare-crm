<x-dashboard-layout>
    @section('header', __('messages.patient_dashboard'))

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200 dark:border-slate-700">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">
                        {{ __('messages.welcome', ['name' => auth()->user()->name]) }} 👋
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ __('messages.manage_appointments_history') }}
                    </p>
                </div>
                <div>
                     <a href="{{ route('patient.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        {{ __('messages.my_appointments') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Quick Stats / Links --}}
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-md text-blue-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                             <dl>
                                <dt class="text-sm font-medium text-slate-500 truncate">{{ __('messages.upcoming_appointments') }}</dt>
                                <dd>
                                    <div class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ __('messages.check_schedule') }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-5 py-3 border-t border-slate-100 dark:border-slate-700">
                    <div class="text-sm">
                        <a href="{{ route('patient.appointments.index') }}" class="font-medium text-blue-600 hover:text-blue-500">{{ __('messages.view_all_appointments') }} &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 p-3 rounded-md text-purple-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 truncate">{{ __('messages.lab_reports') }}</dt>
                                <dd>
                                    <div class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ __('messages.view_results') }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-5 py-3 border-t border-slate-100 dark:border-slate-700">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-gray-400 cursor-not-allowed">{{ __('messages.coming_soon') }}</a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 hover:shadow-md transition">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-md text-green-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                             <dl>
                                <dt class="text-sm font-medium text-slate-500 truncate">{{ __('messages.profile_settings') }}</dt>
                                <dd>
                                    <div class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ __('messages.update_details') }}
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-700/50 px-5 py-3 border-t border-slate-100 dark:border-slate-700">
                    <div class="text-sm">
                        <a href="{{ route('profile.edit') }}" class="font-medium text-green-600 hover:text-green-500">{{ __('messages.edit_profile') }} &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dashboard-layout>
