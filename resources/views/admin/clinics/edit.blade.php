@extends('layouts.dashboard')

@section('header')
<div class="flex justify-between items-center">
    <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-gray-200">
        {{ __('Edit Tenant Clinic') }}
    </h2>
    <a href="{{ route('admin.clinics.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Back to List</a>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <div class="bg-white dark:bg-slate-800 shadow rounded-lg overflow-hidden">
        <form action="{{ route('admin.clinics.update', $clinic) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Clinic Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Clinic Name</label>
                <input type="text" name="name" id="name" value="{{ $clinic->name }}" required
                       class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
            </div>

            <!-- Subdomain -->
            <div>
                <label for="subdomain" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Subdomain</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="subdomain" id="subdomain" value="{{ $clinic->subdomain }}" required
                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-slate-300 bg-slate-50 text-slate-500 dark:bg-slate-600 dark:border-slate-500 dark:text-slate-300 sm:text-sm">
                        .app.com
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Admin Email</label>
                    <input type="email" name="email" id="email" value="{{ $clinic->email }}" required
                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ $clinic->phone }}"
                           class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">
                </div>
            </div>
            
            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Address</label>
                <textarea name="address" id="address" rows="3"
                          class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white sm:text-sm">{{ $clinic->address }}</textarea>
            </div>
            
            <!-- Active Status -->
             <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="is_active" name="is_active" type="checkbox" value="1" {{ $clinic->is_active ? 'checked' : '' }}
                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="is_active" class="font-medium text-slate-700 dark:text-slate-300">Active Status</label>
                    <p class="text-slate-500">Uncheck to suspend this clinic's access.</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Tenant
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
