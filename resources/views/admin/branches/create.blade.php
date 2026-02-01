@extends('layouts.dashboard')

@section('header', 'New Branch')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800">Register New Branch</h2>
        </div>
        
        <form action="{{ route('admin.branches.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Branch Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Downtown Clinic">
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-slate-700">Branch Code (Slug) <span class="text-red-500">*</span></label>
                <input type="text" name="slug" id="slug" required class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. downtown-clinic">
                <p class="mt-1 text-xs text-slate-500">Unique identifier for this location.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                    <input type="text" name="phone" id="phone" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-slate-700">Address</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_main" id="is_main" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_main" class="ml-2 block text-sm text-slate-900">
                    Set as Headquarters (HQ)
                </label>
            </div>

            <div class="flex justify-end pt-6">
                <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 mr-3">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm font-medium transition">
                    Create Branch
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
