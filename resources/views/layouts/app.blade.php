<!DOCTYPE html>
<html lang="en">
<head>
    <title>Healthcare CRM</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="min-h-screen flex flex-col">
        @php
            $role = optional(auth()->user()->role)->slug;
        @endphp

        {{-- Navigation --}}
        <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        {{-- Logo --}}
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                                Healthcare CRM
                            </a>
                        </div>

                        {{-- Links --}}
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.dashboard') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.reports.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    Reports
                                </a>
                                <a href="{{ route('admin.logs.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.logs.*') ? 'border-primary text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                    Audit Logs
                                </a>
                            @endif

                            @if($role === 'doctor')
                                <a href="{{ route('doctor.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-gray-900 text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                    Doctor Dashboard
                                </a>
                            @endif

                             @if($role === 'nurse')
                                <a href="{{ route('nurse.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-gray-900 text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                    Nurse Dashboard
                                </a>
                            @endif

                            @if($role === 'reception')
                                <a href="{{ route('reception.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-gray-900 text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out">
                                    Reception
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- User Dropdown --}}
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <div>
                                <button @click="open = ! open" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </div>

                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
                                 style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); this.closest('form').submit();"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Log Out
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} Healthcare CRM. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
