<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.sign_in_title') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-font-smoothing: antialiased;
            overflow-y: auto;
        }
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex flex-col lg:flex-row min-h-screen">
        
        <!-- Left Side - Visual -->
        <div class="hidden lg:flex w-1/2 relative login-bg overflow-hidden">
            <!-- Clean gradient overlay - no image -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 opacity-95"></div>
            
            <!-- Decorative circles -->
            <div class="absolute top-20 right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-20 flex flex-col justify-between p-16 w-full text-white">
                <div class="flex items-center gap-3">
                    <svg class="w-10 h-10 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span class="font-bold text-3xl tracking-tight drop-shadow-md">CareSync</span>
                </div>

                <div class="max-w-md">
                    <h2 class="text-4xl font-extrabold mb-6 drop-shadow-md leading-tight">{{ __('messages.your_health_connected') }}</h2>
                    <ul class="space-y-5 text-white/90">
                        <li class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20">
                            <svg class="w-6 h-6 mt-0.5 text-green-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span><strong class="text-white block mb-1">{{ __('messages.patients') }}</strong> {{ __('messages.patients_desc') }}</span>
                        </li>
                        <li class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20">
                            <svg class="w-6 h-6 mt-0.5 text-blue-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span><strong class="text-white block mb-1">Providers</strong> {{ __('messages.providers_desc') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="text-sm text-white/80 font-medium tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    {{ __('messages.secure_unified_portal') }}
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="max-w-[400px] w-full">
                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                @if(session('magic_link_url'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-sm text-green-800 font-medium">Magic Link Generated (Demo):</p>
                        <a href="{{ session('magic_link_url') }}" class="text-indigo-600 hover:text-indigo-800 break-all text-xs underline block mt-2">Click here to login</a>
                    </div>
                @endif

                <div class="mb-10">
                    <h1 class="text-3xl font-extrabold text-slate-900 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ __('messages.welcome_back') }}</h1>
                    <p class="mt-3 text-sm text-slate-500 font-medium">{{ __('messages.sign_in_desc') }}</p>
                </div>

                <!-- Quick Login Demo Buttons -->
                <div class="mb-8 grid grid-cols-2 gap-3">
                    <button type="button" onclick="fillLogin('alicewonderland@example.com', 'password')" class="flex items-center justify-center gap-2 px-3 py-3 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <span class="text-lg">👤</span> Patient
                    </button>
                    <button type="button" onclick="fillLogin('drsarahconnor@healthcare.com', 'password')" class="flex items-center justify-center gap-2 px-3 py-3 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <span class="text-lg">🩺</span> Doctor
                    </button>
                    <button type="button" onclick="fillLogin('reception1@healthcare.com', 'password')" class="flex items-center justify-center gap-2 px-3 py-3 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <span class="text-lg">🛎️</span> Reception
                    </button>
                     <button type="button" onclick="fillLogin('admin@admin.com', 'password')" class="flex items-center justify-center gap-2 px-3 py-3 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">
                        <span class="text-lg">⚡</span> Admin
                    </button>
                </div>
                
                <script>
                    function fillLogin(email, password) {
                        document.getElementById('email').value = email;
                        document.getElementById('password').value = password;
                    }
                </script>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.email_address') }}</label>
                        <div class="relative">
                            <input id="email" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all" 
                                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@clinic.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.password') }}</label>
                        <div class="relative">
                            <input id="password" class="block w-full rounded-lg border-0 py-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-all"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                         <!-- Remember Me -->
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600" name="remember">
                            <label for="remember_me" class="ml-2 block text-sm leading-6 text-slate-900">
                                {{ __('messages.remember_me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-semibold text-blue-600 hover:text-blue-500" href="{{ route('password.request') }}">
                                {{ __('messages.forgot_password') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="flex w-full justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-3 py-3.5 text-sm font-semibold leading-6 text-white shadow-xl shadow-indigo-500/25 hover:from-indigo-700 hover:to-purple-700 hover:shadow-2xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
                        {{ __('messages.sign_in_btn') }}
                    </button>

                    <!-- Alternate Login -->
                     <div class="mt-6 text-center relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-2 text-sm text-gray-500">Or continue with</span>
                        </div>
                     </div>
                     <div class="mt-6 text-center">
                        <button type="button" onclick="submitMagicLink()" class="flex w-full items-center justify-center gap-3 rounded-lg bg-white px-3 py-3 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 focus-visible:ring-transparent transition-all">
                            <span class="text-lg">✨</span> Email me a magic link
                        </button>
                    </div>
                </form>

                <script>
                    function submitMagicLink() {
                        const email = document.getElementById('email').value;
                        if(!email) { 
                            // Shake animation or toast
                            const emailInput = document.getElementById('email');
                            emailInput.classList.add('ring-red-500', 'ring-2');
                            setTimeout(() => emailInput.classList.remove('ring-red-500', 'ring-2'), 500);
                            emailInput.focus();
                            return; 
                        }
                        
                        const form = document.querySelector('form');
                        form.action = "{{ route('login.magic') }}";
                        form.submit();
                    }
                </script>

                <p class="mt-8 text-center text-sm text-slate-500">
                    {{ __('messages.no_account') }} 
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">{{ __('messages.create_account') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
