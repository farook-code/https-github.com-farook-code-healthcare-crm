<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <title>CareSync - Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4f46e5">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; }
        [dir="rtl"] body { font-family: 'Cairo', sans-serif; }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-900 font-sans antialiased text-slate-800 dark:text-slate-100" x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <div class="md:hidden flex items-center justify-between bg-white border-b border-slate-200 px-4 py-3 sticky top-0 z-30 shadow-sm">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <span class="font-bold text-xl text-slate-800">CareSync</span>
        </div>
        <div>
             <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold border border-blue-200">
                {{ substr(auth()->user()->name, 0, 1) }}
             </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden bg-slate-50">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 text-slate-800 dark:text-white transition-transform duration-300 ease-in-out md:translate-x-0 md:fixed md:inset-y-0 flex flex-col shrink-0 shadow-xl">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-2">
                    @if(\App\Models\Settings::get('app_logo'))
                        <img src="{{ asset('storage/' . \App\Models\Settings::get('app_logo')) }}" class="h-8 w-8 object-contain">
                    @else
                        <div class="bg-blue-600 p-1.5 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    @endif
                    <span class="text-lg font-bold tracking-tight">{{ \App\Models\Settings::get('app_name', 'CareSync') }}</span>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto pb-20 custom-scrollbar">
                
                @php $role = optional(auth()->user()->role)->slug; @endphp
                
                @if($role === 'pharmacist')
                    <a href="{{ route('pharmacist.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('pharmacist.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-1">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.medicines.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.medicines.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-1">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        <span class="font-medium">Inventory</span>
                    </a>
                    <a href="{{ route('reception.invoices.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.invoices.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-1">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-medium">Billing</span>
                    </a>
                @endif
                
                @if($role === 'lab_technician')
                     <a href="{{ route('lab.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('lab.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-1">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                     <a href="{{ route('lab-reports.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('lab-reports.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-1">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        <span class="font-medium">Lab Reports</span>
                    </a>
                    <a href="{{ route('reception.invoices.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.invoices.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-1">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-medium">Billing</span>
                    </a>
                @endif
                
                {{-- Removed redundant Plans link --}}

                <!-- COMMON LINKS -->
                @if($role === 'admin' || $role === 'super-admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mb-4">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="font-medium">{{ __('messages.dashboard') }}</span>
                    </a>
                    
                    <!-- Patients Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span class="font-medium">Patients</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.users.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                All Patients & Users
                            </a>
                        </div>
                    </div>

                    <!-- OPD & Labs Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.ot.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                                <span class="font-medium">OPD & Labs</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <a href="{{ route('admin.ot.bookings.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.ot.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                Operation Theater
                            </a>
                             {{-- Lab Reports link generally exists for Staff, but Admin can view via Reports or specific paths --}}
                        </div>
                    </div>

                    <!-- In-Patient (Beds) Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.ipd.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <span class="font-medium">In-Patient (Beds)</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <a href="{{ route('admin.ipd.admissions.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.ipd.admissions.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                Admissions
                            </a>
                            <a href="{{ route('admin.ipd.beds.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.ipd.beds.*') || request()->routeIs('admin.ipd.wards.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                Wards & Beds
                            </a>
                        </div>
                    </div>

                    <!-- Pharmacy Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.medicines.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                <span class="font-medium">Pharmacy</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                             @if(auth()->user()->hasFeature('module_pharmacy_stock'))
                                <a href="{{ route('admin.medicines.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.medicines.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    Pharmacy Stock
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Billing & Finance Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.invoices.*') || request()->routeIs('reception.invoices.create') || request()->routeIs('admin.insurance.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="font-medium">Billing & Finance</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <!-- Invoices Sub-Group -->
                            <div class="space-y-1">
                                <a href="{{ route('admin.invoices.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.invoices.index') && !request('category') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    {{ __('All Invoices') }}
                                </a>
                                {{-- Category Quick Links --}}
                                <a href="{{ route('admin.invoices.index', ['category' => 'opd']) }}" class="block pl-6 pr-3 py-1.5 rounded-md text-xs {{ request('category') == 'opd' ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    OPD Billing
                                </a>
                                <a href="{{ route('admin.invoices.index', ['category' => 'pharmacy']) }}" class="block pl-6 pr-3 py-1.5 rounded-md text-xs {{ request('category') == 'pharmacy' ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    Pharmacy Billing
                                </a>
                                <a href="{{ route('admin.invoices.index', ['category' => 'ipd']) }}" class="block pl-6 pr-3 py-1.5 rounded-md text-xs {{ request('category') == 'ipd' ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    IPD Billing
                                </a>
                                <a href="{{ route('admin.invoices.index', ['category' => 'lab']) }}" class="block pl-6 pr-3 py-1.5 rounded-md text-xs {{ request('category') == 'lab' ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    Lab / Pathology Billing
                                </a>
                            </div>

                            <a href="{{ route('reception.invoices.create') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('reception.invoices.create') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('+ Create Invoice') }}
                            </a>
                            <a href="{{ route('admin.insurance.providers.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.insurance.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('Insurance') }}
                            </a>
                        </div>
                    </div>

                    <!-- Plans & Services Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.plans.*') || request()->routeIs('admin.subscriptions.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                <span class="font-medium">Plans & Services</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <a href="{{ route('admin.plans.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.plans.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('Packages & Plans') }}
                            </a>
                            <a href="{{ route('admin.subscriptions.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.subscriptions.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                Subscriptions
                            </a>
                        </div>
                    </div>

                    <!-- Administration Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.doctors.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.branches.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <span class="font-medium">Administration</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <a href="{{ route('admin.doctors.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.doctors.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('messages.doctors') }}
                            </a>
                            <a href="{{ route('admin.departments.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.departments.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('messages.departments') }}
                            </a>
                            @if(auth()->user()->role->slug === 'super-admin')
                                <a href="{{ route('admin.branches.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.branches.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                    Branch Network
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Analytics Group -->
                    <div x-data="{ open: {{ request()->routeIs('admin.reports.*') || request()->routeIs('admin.logs.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-all group mt-1">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
                                <span class="font-medium">{{ __('messages.analytics') }}</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                         <div x-show="open" class="pl-11 pr-2 space-y-1 mt-1">
                            <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.reports.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('messages.reports') }}
                            </a>
                            <a href="{{ route('admin.logs.index') }}" class="block px-3 py-2 rounded-md text-sm {{ request()->routeIs('admin.logs.*') ? 'text-white bg-blue-600' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
                                {{ __('messages.audit_logs') }}
                            </a>
                        </div>
                    </div>
                @endif

                @if($role === 'doctor')
                    <a href="{{ route('doctor.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('doctor.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="font-medium">{{ __('messages.dashboard') }}</span>
                    </a>
                    <a href="{{ route('doctor.appointments') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('doctor.appointments*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="font-medium">{{ __('messages.appointments') }}</span>
                    </a>
                    <a href="{{ route('doctor.invoices') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('doctor.invoices') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-medium">{{ __('Billing') }}</span>
                    </a>
                @endif

                @if($role === 'reception')
                    <a href="{{ route('reception.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="font-medium">{{ __('messages.dashboard') }}</span>
                    </a>
                    <a href="{{ route('reception.appointments.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.appointments*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="font-medium">{{ __('messages.calendar') }}</span>
                    </a>
                    <a href="{{ route('reception.patients.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.patients.*') && !request()->routeIs('reception.patients.create') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <span class="font-medium">{{ __('messages.patients') }}</span>
                    </a>
                    <a href="{{ route('reception.patients.create') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.patients*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                        <span class="font-medium">{{ __('messages.register_patient') }}</span>
                    </a>
                    <a href="{{ route('reception.waitlist.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.waitlist.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-medium">{{ __('messages.waitlist') }}</span>
                    </a>
                    @if(auth()->user()->hasFeature('module_pharmacy_stock'))
                        <a href="{{ route('admin.medicines.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.medicines.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            <span class="font-medium">{{ __('messages.pharmacy') }}</span>
                        </a>
                    @endif
                    <a href="{{ route('reception.invoices.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.invoices.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-medium">{{ __('messages.invoices_billing') ?? 'Invoices' }}</span>
                    </a>
                    <a href="{{ route('reception.insurance.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('reception.insurance.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span class="font-medium">{{ __('messages.insurance_claims') }}</span>
                    </a>
                @endif
                
                @if($role === 'patient')
                    <a href="{{ route('patient.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('patient.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="font-medium">{{ __('messages.dashboard') }}</span>
                    </a>
                    <a href="{{ route('patient.appointments.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('patient.appointments*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="font-medium">{{ __('messages.my_appointments') }}</span>
                    </a>

                    <a href="{{ route('patient.admissions.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('patient.admissions*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <span class="font-medium">My Admissions (IPD)</span>
                    </a>

                    <a href="{{ route('patient.prescriptions.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('patient.prescriptions*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        <span class="font-medium">My Prescriptions</span>
                    </a>

                    <a href="{{ route('patient.card.show') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('patient.card.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                        <span class="font-medium">{{ __('messages.my_health_id') }}</span>
                    </a>
                @endif
                
                @if($role === 'nurse')
                    <a href="{{ route('nurse.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('nurse.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        <span class="font-medium">{{ __('messages.dashboard') }}</span>
                    </a>
                @endif
                
                @if(auth()->user()->hasFeature('chat_access'))
                    <a href="{{ route('chat.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('chat.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group mt-6 border-t border-slate-800 pt-6 justify-between">
                         <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                            <span class="font-medium">{{ __('messages.messages') }}</span>
                         </div>
                         <span id="global-unread-badge" class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full hidden">
                            0
                         </span>
                    </a>
                @endif

                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white' }} transition-all group">
                     <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="font-medium">{{ __('messages.settings') }}</span>
                </a>
            </nav>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let pollInterval = 30000; // 30 seconds for scalability
            
            function checkUnread() {
                // Optimization: Don't poll if tab is inactive
                if (document.hidden) return;

                fetch('{{ route('chat.unread') }}')
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('global-unread-badge');
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    })
                    .catch(console.error);
            }
            
            // Initial check
            checkUnread();
            
            // Poll
            setInterval(checkUnread, pollInterval);
        });
    </script>

            <!-- Sidebar Footer -->
            <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        {{ __('messages.sign_out') }}
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 dark:bg-slate-900 flex flex-col min-w-0 md:ml-64 no-scrollbar">
            <!-- Top Header (Desktop) -->
            <header class="hidden md:flex items-center justify-between bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 p-[20px] h-24 sticky top-0 z-20 shadow-sm" style="padding:15px;">
                <h2 class="text-xl font-bold text-slate-800 dark:text-gray-100">
                    @yield('header', 'Dashboard')
                </h2>
                <div class="flex items-center gap-4">
                    {{-- System Alerts --}}
                    {{-- System Alerts (Moved to Scripts) --}}
                    
                    {{-- Notifications Bell --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-amber-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1.5 right-1.5 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-600"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-lg shadow-xl overflow-hidden z-50 border border-slate-100 dark:border-slate-700" x-cloak>
                            <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Notifications</h3>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">Mark all read</button>
                                    </form>
                                @endif
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors border-b border-slate-50 dark:border-slate-700">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0 pt-0.5">
                                               @php $type = $notification->data['type'] ?? 'info'; @endphp
                                               @if($type == 'appointment')
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    </div>
                                               @elseif($type == 'lab')
                                                    <div class="h-8 w-8 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                                    </div>
                                               @elseif($type == 'warning' || $type == 'stock')
                                                    <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                    </div>
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </div>
                                               @endif
                                            </div>
                                            <div class="ml-3 w-0 flex-1">
                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                                <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-sm text-slate-500">
                                        No new notifications
                                    </div>
                                @endforelse
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900 px-4 py-2 text-center border-t border-slate-100 dark:border-slate-700">
                                <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400">View All</a>
                            </div>
                        </div>
                    </div>

                    {{-- Language Switcher --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-colors p-2">
                            <span class="mr-1 uppercase font-bold text-sm">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white dark:bg-slate-700 rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5" style="display: none;">
                            <a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">English</a>
                            <a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">Español</a>
                            <a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">Français</a>
                            <a href="{{ route('lang.switch', 'ar') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-600">العربية</a>
                        </div>
                    </div>

                     {{-- Dark Mode Toggle Desktop --}}
                     <button onclick="toggleDarkMode()" class="text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-yellow-400 transition-colors p-2 rounded-full hover:bg-slate-100 dark:hover:bg-slate-700">
                        <svg id="moon-icon" class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        <svg id="sun-icon" class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    </button>
                </div>
            </header>

            <div class="p-4 sm:p-6 md:p-8">
                 {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                             <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm text-yellow-700 font-medium">{{ session('warning') }}</p>
                        </div>
                        <button @click="show = false" class="text-yellow-500 hover:text-yellow-700">
                             <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endif

                
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                        <p class="text-sm text-red-700 font-bold mb-1">{{ __('messages.please_fix_errors') }}</p>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
    <x-alert-system />
    @stack('scripts')
    <script>
        function toggleDarkMode() {
            if (localStorage.theme === 'dark') {
                localStorage.theme = 'light';
                document.documentElement.classList.remove('dark');
            } else {
                localStorage.theme = 'dark';
                document.documentElement.classList.add('dark');
            }
        }
    </script>
</body>
</html>
