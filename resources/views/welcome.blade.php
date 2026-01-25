<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HealthFlow | Enterprise Healthcare Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-font-smoothing: antialiased;
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#e5e7eb 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="antialiased text-slate-900 bg-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center text-blue-900">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="ml-2 font-bold text-xl tracking-tight">HealthFlow</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
                    <a href="#features" class="hover:text-blue-900 transition-colors">Solutions</a>
                    <a href="#" class="hover:text-blue-900 transition-colors">Enterprise</a>
                    <a href="#" class="hover:text-blue-900 transition-colors">Resources</a>
                    <a href="#" class="hover:text-blue-900 transition-colors">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-blue-900 hover:text-blue-700">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-900">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-md bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 transition shadow-sm ring-1 ring-blue-900 ring-offset-2">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="pt-32 pb-16 lg:pt-40 lg:pb-32 hero-pattern border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-800 text-xs font-semibold mb-6 border border-blue-100 uppercase tracking-wide">
                    Trusted by 500+ Healthcare Institutions
                </span>
                
                <h1 class="text-5xl lg:text-7xl font-bold tracking-tight text-slate-900 mb-8 leading-tight">
                    The Operating System for <br>
                    <span class="text-blue-900">Modern Healthcare.</span>
                </h1>
                
                <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto">
                    A unified platform to streamline clinical workflows, patient engagement, and administrative operations. Secure, scalable, and compliant.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-blue-900 text-white text-base font-semibold rounded-md hover:bg-blue-800 transition shadow-sm">
                        Start Free Trial
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 border border-slate-300 text-base font-semibold rounded-md hover:bg-slate-50 transition shadow-sm">
                        Schedule Demo
                    </a>
                </div>

                <div class="mt-12 flex items-center justify-center gap-8 grayscale opacity-60">
                     <!-- Simple placeholders for corporate logos -->
                     <span class="font-bold text-xl text-slate-400">MAYO CLINIC</span>
                     <span class="font-bold text-xl text-slate-400">CLEVELAND</span>
                     <span class="font-bold text-xl text-slate-400">JOHNS HOPKINS</span>
                     <span class="font-bold text-xl text-slate-400">KAISER</span>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-16 lg:mt-24 relative rounded-xl bg-slate-900 p-2 shadow-2xl ring-1 ring-slate-900/5">
                 <div class="bg-slate-800 rounded-lg overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2670&q=80" alt="App Dashboard" class="w-full h-auto object-cover opacity-90">
                </div>
            </div>
        </div>
    </main>

    <!-- Key Metrics / Features -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-base font-semibold text-blue-900 uppercase tracking-wide">Enterprise Grade</h2>
                <p class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">Platform Capabilities</p>
                <p class="mt-4 text-lg text-slate-500">Built to handle the complexity of large-scale medical operations while remaining simple for providers.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <!-- Feature 1 -->
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-900 text-white">
                       <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <div class="ml-16">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">Unified Health Records</h3>
                        <p class="mt-2 text-base text-slate-500">Consolidate patient history, labs, and diagnostics into a single, searchable timeline view.</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-900 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <div class="ml-16">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">HIPAA Compliant</h3>
                        <p class="mt-2 text-base text-slate-500">Enterprise-grade security with role-based access control (RBAC) and audit logging.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-900 text-white">
                         <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="ml-16">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">Operational Efficiency</h3>
                        <p class="mt-2 text-base text-slate-500">Reduce administrative overhead with automated appointment scheduling and billing.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
            <div class="flex justify-center space-x-6 md:order-2">
                <a href="#" class="text-slate-400 hover:text-slate-500">Privacy</a>
                <a href="#" class="text-slate-400 hover:text-slate-500">Terms</a>
                <a href="#" class="text-slate-400 hover:text-slate-500">Support</a>
            </div>
            <div class="mt-8 md:mt-0 md:order-1">
                <p class="text-center text-base text-slate-400">&copy; {{ date('Y') }} HealthFlow Systems. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
