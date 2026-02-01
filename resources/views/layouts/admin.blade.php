<!DOCTYPE html>
<html lang="en">
<head>
    <title>CareSync - Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <div class="md:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <span class="font-bold text-xl text-gray-800">Admin</span>
        </div>
        <div>
             <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
             </div>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-auto flex flex-col shrink-0">
            
            <!-- Sidebar Header -->
            <div class="flex items-center justify-center h-16 bg-slate-800 shadow-md">
                <span class="text-xl font-bold tracking-wider">CareSync <span class="text-blue-400">Admin</span></span>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <span class="text-lg mr-3">📊</span>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('chat.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('chat.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                     <span class="text-lg mr-3">💬</span>
                    <span class="font-medium">Messages</span>
                </a>

                <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Management</div>

                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <span class="text-lg mr-3">👥</span>
                    <span class="font-medium">Users</span>
                </a>
                
                <a href="{{ route('admin.doctors.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.doctors.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <span class="text-lg mr-3">👨‍⚕️</span>
                    <span class="font-medium">Doctors</span>
                </a>

                 <a href="{{ route('admin.departments.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.departments.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <span class="text-lg mr-3">🏢</span>
                    <span class="font-medium">Departments</span>
                </a>

                <div class="px-4 mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Analytics</div>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <span class="text-lg mr-3">📈</span>
                    <span class="font-medium">Reports</span>
                </a>

                <a href="{{ route('admin.logs.index') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.logs.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} transition-colors group">
                    <span class="text-lg mr-3">🛡️</span>
                    <span class="font-medium">Audit Logs</span>
                </a>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 bg-slate-800 border-t border-slate-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-slate-300 rounded-lg hover:bg-slate-700 hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay for Mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black bg-opacity-50 md:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 flex flex-col">
            <!-- Top Header (Desktop) -->
            <header class="hidden md:flex items-center justify-between bg-white border-b border-gray-200 px-8 py-4 sticky top-0 z-10">
                <h2 class="text-xl font-semibold text-gray-800">
                    @yield('header', 'Admin Dashboard')
                </h2>
                <div class="flex items-center gap-4">
                     <span class="text-sm text-gray-500">Welcome, {{ auth()->user()->name }}</span>
                     <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                     </div>
                </div>
            </header>

            <div class="p-6 md:p-8">
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

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
