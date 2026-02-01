@extends('layouts.dashboard')

@section('header')
<h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-gray-200">
    {{ __('Settings & Branding') }}
</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-6">
    
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">White Labeling</h3>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Customize the look and feel of your clinic's CRM.</p>
        </div>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <!-- Clinic Name -->
            <div>
                <label for="app_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Clinic / App Name</label>
                <input type="text" name="app_name" id="app_name" 
                       value="{{ \App\Models\Settings::get('app_name', 'HealthFlow CRM') }}"
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
            </div>
            
            <!-- Logo -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Logo</label>
                <div class="mt-1 flex items-center space-x-5">
                    <div class="flex-shrink-0 h-16 w-16 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center overflow-hidden border border-slate-200 dark:border-slate-600">
                         @if(\App\Models\Settings::get('app_logo'))
                            <img src="{{ asset('storage/' . \App\Models\Settings::get('app_logo')) }}" alt="Logo" class="h-full w-full object-contain">
                         @else
                            <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                         @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" name="app_logo" accept="image/*"
                               class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-slate-500">PNG, JPG up to 2MB.</p>
                    </div>
                </div>
            </div>
            
            <!-- Primary Color -->
            <div>
                <label for="primary_color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Primary Theme Color</label>
                <div class="mt-1 flex items-center gap-3">
                    <input type="color" name="primary_color" id="primary_color"
                           value="{{ \App\Models\Settings::get('primary_color', '#4f46e5') }}"
                           class="h-9 w-9 p-1 rounded-md border border-slate-300 cursor-pointer shadow-sm">
                    <input type="text" value="{{ \App\Models\Settings::get('primary_color', '#4f46e5') }}" readonly
                           class="block w-24 rounded-md border-slate-300 shadow-sm sm:text-sm bg-slate-50 text-slate-500">
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Integrations Section -->
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden mt-6">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                     <h3 class="text-lg font-medium text-slate-900 dark:text-white">Integrations</h3>
                     <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Manage external service connections.</p>
                </div>
                <div class="h-8 w-8 text-green-500">
                     <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <div class="col-span-12">
                <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">WhatsApp Cloud API</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <!-- Phone Number ID -->
                    <div>
                        <label for="whatsapp_phone_number_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone Number ID</label>
                        <input type="text" name="whatsapp_phone_number_id" id="whatsapp_phone_number_id" 
                               value="{{ \App\Models\Settings::get('whatsapp_phone_number_id') }}"
                               placeholder="e.g. 104xxxxxxxxxxxxx"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
                    </div>

                    <!-- Business Account ID (Optional usually, but good to have) -->
                    <div>
                        <label for="whatsapp_business_account_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Business Account ID</label>
                        <input type="text" name="whatsapp_business_account_id" id="whatsapp_business_account_id" 
                               value="{{ \App\Models\Settings::get('whatsapp_business_account_id') }}"
                               placeholder="e.g. 101xxxxxxxxxxxxx"
                               class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
                    </div>
                    
                    <!-- Access Token -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="whatsapp_access_token" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Permanent Access Token</label>
                         <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="password" name="whatsapp_access_token" id="whatsapp_access_token" 
                                   value="{{ \App\Models\Settings::get('whatsapp_access_token') }}"
                                   placeholder="EAAG..."
                                   class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Get this from the Meta for Developers dashboard.</p>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
             <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Integrations
                </button>
            </div>
        </form>
        
        <!-- Test Connection -->
        <div class="bg-slate-50 dark:bg-slate-700 px-6 py-4 border-t border-slate-200 dark:border-slate-600">
            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">Test Connection</h4>
            <form action="{{ route('admin.settings.test-whatsapp') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <input type="text" name="test_phone" placeholder="Recipient Phone (e.g. 15551234567)" required
                       class="block w-full max-w-xs rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-600 dark:border-slate-500 dark:text-white sm:text-sm">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 border-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-500 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send Test Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
