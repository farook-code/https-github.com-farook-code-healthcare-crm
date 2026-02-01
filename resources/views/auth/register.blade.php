<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.create_account_title') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
    </style>
</head>
<body class="h-full">
    <div class="min-h-screen flex">
        
        <!-- Left Side - Visual -->
        <div class="hidden lg:flex w-1/2 relative bg-blue-900 overflow-hidden">
            <div class="absolute inset-0 bg-blue-900/90 z-10"></div>
            <!-- Professional Medical Image -->
            <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?ixlib=rb-4.0.3&auto=format&fit=crop&w=2091&q=80" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40" alt="Medical Team">
            
            <div class="relative z-20 flex flex-col justify-between p-16 w-full text-white">
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span class="font-bold text-2xl tracking-tight">CareSync</span>
                </div>

                <div class="max-w-md">
                    <h2 class="text-3xl font-bold mb-4">{{ __('messages.join_caresync') }}</h2>
                    <p class="text-blue-100 text-lg mb-8">{{ __('messages.secure_account_desc') }}</p>
                    
                    <ul class="space-y-4 text-blue-100">
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center border border-blue-400/30">
                                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>{{ __('messages.manage_appointments_feature') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center border border-blue-400/30">
                                <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>{{ __('messages.secure_private_feature') }}</span>
                        </li>
                    </ul>
                </div>

                <div class="text-xs text-blue-300 font-medium tracking-wide uppercase">
                    {{ __('messages.unified_registration') }}
                </div>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white overflow-y-auto">
            <div class="max-w-[440px] w-full">
                
                <div class="mb-10">
                    <h1 class="text-2xl font-bold text-slate-900">{{ __('messages.create_account') }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ __('messages.sign_up_start') }}</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="plan" value="{{ request('plan') }}">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.full_name') }}</label>
                        <input id="name" class="appearance-none block w-full px-3 py-2.5 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.email_address') }}</label>
                        <input id="email" class="appearance-none block w-full px-3 py-2.5 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.password') }}</label>
                        <input id="password" class="appearance-none block w-full px-3 py-2.5 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">{{ __('messages.confirm_password') }}</label>
                        <input id="password_confirmation" class="appearance-none block w-full px-3 py-2.5 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="terms" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                        <label for="terms" class="text-sm text-slate-600">
                            {{ __('messages.agree_terms') }}
                        </label>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 transition-colors">
                        {{ __('messages.submit_request') }}
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-slate-500">
                    {{ __('messages.already_have_id') }} 
                    <a href="{{ route('login') }}" class="font-medium text-blue-900 hover:text-blue-700">{{ __('messages.sign_in') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
