<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CareSync | Enterprise Healthcare Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        html {
            overflow-y: auto;
            height: auto;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-font-smoothing: antialiased;
            overflow-y: auto;
            height: auto;
            min-height: 100vh;
        }
        .hero-pattern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-image: 
                linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%),
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0, transparent 50%), 
                radial-gradient(at 50% 0%, rgba(139, 92, 246, 0.1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.1) 0, transparent 50%);
            background-color: #ffffff;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased text-slate-900 bg-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center text-indigo-600">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="ml-2 font-bold text-2xl tracking-tight bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">CareSync</span>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex gap-8 text-sm font-medium text-slate-600">
                    <a href="#features" class="hover:text-indigo-600 transition-colors">{{ __('messages.solutions') }}</a>
                    <a href="#pricing" class="hover:text-indigo-600 transition-colors">{{ __('messages.enterprise') }}</a>
                    <a href="#" class="hover:text-indigo-600 transition-colors">{{ __('messages.resources') }}</a>
                    <a href="#" class="hover:text-indigo-600 transition-colors">{{ __('messages.contact') }}</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">{{ __('messages.go_to_dashboard') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">{{ __('messages.log_in') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/25 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 duration-200">
                                {{ __('messages.get_started') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="pt-32 pb-20 lg:pt-40 lg:pb-32 hero-pattern border-b border-slate-100 relative overflow-hidden">
        <!-- Decorative blob -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-gradient-to-r from-indigo-50 to-purple-50 text-indigo-700 text-xs font-semibold mb-6 border border-indigo-100 uppercase tracking-wide shadow-sm">
                    ✨ {{ __('messages.trusted_by') }}
                </span>
                
                <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-tight">
                    {{ __('messages.hero_title') }} <br>
                    <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">{{ __('messages.hero_subtitle') }}</span>
                </h1>
                
                <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto font-medium">
                    {{ __('messages.hero_desc') }}
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-base font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-xl shadow-indigo-500/25 hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-1 duration-200">
                        {{ __('messages.start_free_trial') }}
                        <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-white text-slate-700 border-2 border-slate-200 text-base font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 duration-200">
                        {{ __('messages.schedule_demo') }}
                    </a>
                </div>

                <div class="mt-16 flex items-center justify-center gap-8 grayscale opacity-50 hover:opacity-70 transition-opacity">
                     <!-- Simple placeholders for corporate logos -->
                     <span class="font-bold text-lg text-slate-400">MAYO CLINIC</span>
                     <span class="font-bold text-lg text-slate-400">CLEVELAND</span>
                     <span class="font-bold text-lg text-slate-400">JOHNS HOPKINS</span>
                     <span class="font-bold text-lg text-slate-400">KAISER</span>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="mt-20 lg:mt-28 relative rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 p-2.5 shadow-2xl ring-1 ring-slate-900/10 hover:shadow-3xl transition-shadow duration-300">
                 <div class="bg-slate-800 rounded-xl overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=2670&q=80" alt="App Dashboard" class="w-full h-auto object-cover opacity-90 hover:opacity-100 transition-opacity duration-300">
                </div>
            </div>
        </div>
    </main>

    <!-- Key Metrics / Features -->
    <section id="features" class="py-12 pb-0 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-10">
                <h2 class="text-base font-semibold text-blue-900 uppercase tracking-wide">{{ __('messages.enterprise_grade') }}</h2>
                <p class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">{{ __('messages.platform_capabilities') }}</p>
                <p class="mt-4 text-lg text-slate-500">{{ __('messages.platform_desc') }}</p>
            </div>

            <div class="grid md:grid-cols-3 gap-12">
                <!-- Feature 1 -->
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-900 text-white">
                       <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                    <div class="ml-16">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">{{ __('messages.unified_records') }}</h3>
                        <p class="mt-2 text-base text-slate-500">{{ __('messages.unified_records_desc') }}</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-900 text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <div class="ml-16">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">{{ __('messages.hipaa_compliant') }}</h3>
                        <p class="mt-2 text-base text-slate-500">{{ __('messages.hipaa_desc') }}</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="relative">
                    <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-blue-900 text-white">
                         <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="ml-16">
                        <h3 class="text-lg leading-6 font-semibold text-slate-900">{{ __('messages.operational_efficiency') }}</h3>
                        <p class="mt-2 text-base text-slate-500">{{ __('messages.operational_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    <!-- Pricing Section -->
    <section id="pricing" class="py-24 pb-0 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="padding-bottom: 60px;">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base font-semibold text-blue-900 uppercase tracking-wide">Flexible Pricing</h2>
                <p class="mt-2 text-3xl font-bold text-slate-900 sm:text-4xl">Plans for every clinic size</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    @php
                        $isPopular = $plan->slug == 'premium' || $plan->slug == 'pro';
                        $isCustom = $plan->price >= 1000; // Arbitrary logic for "Enterprise"
                    @endphp
                    <div class="rounded-2xl shadow-sm border {{ $isPopular ? 'bg-blue-900 border-blue-900 text-white transform md:-translate-y-4 shadow-xl' : 'bg-white border-slate-200 hover:shadow-lg' }} p-8 relative overflow-hidden transition">
                        @if($isPopular)
                            <div class="absolute top-0 right-0 bg-yellow-400 text-blue-900 text-xs font-bold px-3 py-1 rounded-bl-lg">POPULAR</div>
                        @endif
                        
                        <h3 class="text-xl font-bold {{ $isPopular ? 'text-white' : 'text-slate-900' }}">{{ $plan->name }}</h3>
                        <p class="mt-2 text-sm {{ $isPopular ? 'text-blue-200' : 'text-slate-500' }}">Perfect for growing teams.</p>
                        
                        <div class="mt-6 flex items-baseline">
                            @if($plan->price == 0)
                                <span class="text-4xl font-bold tracking-tight {{ $isPopular ? '' : 'text-slate-900' }}">Free</span>
                            @else
                                <span class="text-4xl font-bold tracking-tight {{ $isPopular ? '' : 'text-slate-900' }}">${{ $plan->price }}</span>
                                <span class="ml-1 text-xl font-semibold {{ $isPopular ? 'text-blue-200' : 'text-slate-500' }}">/mo</span>
                            @endif
                        </div>

                        <ul class="mt-8 space-y-4 text-sm {{ $isPopular ? 'text-blue-100' : 'text-slate-600' }}">
                            @php
                                $featureMap = [
                                    'limit_doctors_1' => '1 Doctor Account',
                                    'limit_doctors_5' => 'Up to 5 Doctors',
                                    'limit_doctors_unlimited' => 'Unlimited Doctors',
                                    'limit_patients_unlimited' => 'Unlimited Patients',
                                    'limit_staff_unlimited' => 'Unlimited Staff Access',
                                    'module_appointments' => 'Appointment Scheduling',
                                    'module_pharmacy_stock' => 'Pharmacy & Stock',
                                    'module_lab_reporting' => 'Lab Reporting',
                                    'module_chat' => 'Internal Chat System',
                                    'module_staff_access' => 'Nurse & Reception Access',
                                    'module_insurance_claims' => 'Insurance Claims',
                                    'module_audit_logs' => 'Compliance Audit Logs',
                                    'module_api_access' => 'API Access',
                                    'support_email' => 'Email Support',
                                    'support_priority_247' => '24/7 Priority Support',
                                    'analytics_advanced' => 'Advanced Analytics',
                                    'custom_white_label' => 'White-Label Branding',
                                    'module_multi_branch' => 'Multi-Branch Management',     // <--- Added
                                    'limit_branches_unlimited' => 'Unlimited Locations',     // <--- Added
                                    'centralized_dashboard' => 'HQ Dashboard',              // <--- Added
                                    'global_patient_records' => 'Global Patient Records',   // <--- Added
                                ];
                            @endphp

                            @foreach($plan->features as $feature)
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 {{ $isPopular ? 'text-blue-300' : 'text-green-500' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 
                                    {{ $featureMap[$feature] ?? ucfirst(str_replace('_', ' ', $feature)) }}
                                </li>
                            @endforeach
                        </ul>
                        
                        <div class="mt-8">
                            @auth
                                <a href="{{ route('subscriptions.checkout', $plan->id) }}" class="block w-full text-center font-bold py-3 rounded-lg transition {{ $isPopular ? 'bg-white text-blue-900 hover:bg-gray-50' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                                    Upgrade / Select
                                </a>
                            @else
                                <a href="{{ route('register', ['plan' => $plan->slug]) }}" class="block w-full text-center font-bold py-3 rounded-lg transition {{ $isPopular ? 'bg-white text-blue-900 hover:bg-gray-50' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                                    {{ $plan->price == 0 ? 'Start Free' : 'Get Started' }}
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Alpine Modal for Demo -->
    <div x-data="{ open: false }" @keydown.escape.window="open = false">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-show="open" style="display: none;">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6" @click.away="open = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Request a Demo</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Work Email</label>
                        <input type="email" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Clinic Name</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                     <div>
                        <label class="block text-sm font-medium text-slate-700">Phone</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="button" @click="open = false; alert('Thank you! Our sales team will contact you shortly.')" class="w-full bg-blue-900 text-white font-bold py-2.5 rounded-lg hover:bg-blue-800">
                        Submit Request
                    </button>
                </form>
            </div>
        </div>

        <!-- Trigger Button Hook -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const demoButtons = document.querySelectorAll('a[href="#features"]'); // Hijack the demo buttons
                demoButtons.forEach(btn => {
                    if(btn.textContent.includes('Demo')) {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            document.querySelector('[x-data]').__x.$data.open = true;
                        });
                    }
                });
            });
        </script>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-10" style="margin-bottom: 20px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 pb-0">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Brand Column -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="font-bold text-xl text-indigo-600">CareSync</span>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed mb-4">
                        Enterprise healthcare management platform trusted by leading medical institutions worldwide.
                    </p>
                    <div class="flex gap-2">
                        <a href="#" class="w-8 h-8 rounded border border-slate-300 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded border border-slate-300 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded border border-slate-300 flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:border-indigo-600 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">Features</a></li>
                        <li><a href="#pricing" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">Pricing</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">Resources</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">Contact</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">{{ __('messages.privacy') }}</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">{{ __('messages.terms') }}</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">Security</a></li>
                        <li><a href="#" class="text-slate-600 hover:text-indigo-600 transition-colors text-sm">{{ __('messages.support') }}</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="mt-12 pt-8 border-t border-slate-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 text-sm">© {{ date('Y') }} CareSync. All rights reserved.</p>
                    <div class="flex items-center gap-2 text-green-600">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-sm">HIPAA Compliant</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
