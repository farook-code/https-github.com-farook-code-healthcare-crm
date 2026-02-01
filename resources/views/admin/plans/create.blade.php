@extends('layouts.dashboard')

@section('header', 'Create Plan')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <div class="mb-6">
        <a href="{{ route('admin.plans.index') }}" class="text-indigo-600 hover:underline">&larr; Back to Plans</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Create New Package</h2>

        <form action="{{ route('admin.plans.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Plan Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="e.g. Premium Plan">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug (Identifier)</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                        placeholder="e.g. premium (Leave empty to auto-generate from name)">
                    <p class="text-xs text-gray-500 mt-1">Important: Use 'basic', 'premium', or 'pro' to match system feature checks.</p>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Price and Duration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required 
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                            placeholder="0.00">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="duration_in_days" class="block text-sm font-medium text-gray-700 mb-1">Duration (Days)</label>
                        <input type="number" name="duration_in_days" id="duration_in_days" value="{{ old('duration_in_days', 30) }}" required 
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        @error('duration_in_days') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Features -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Features (Display List)</label>
                    <div id="features-container" class="space-y-3">
                        <div class="flex items-center gap-2">
                             <input type="text" name="features[]" class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="e.g. Advanced Reports">
                             <button type="button" onclick="removeFeature(this)" class="text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
                        </div>
                    </div>
                    <button type="button" onclick="addFeature()" class="mt-3 text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Add Another Feature</button>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 font-medium shadow-sm transition">
                    Create Plan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2';
        div.innerHTML = `
            <input type="text" name="features[]" class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Feature description">
            <button type="button" onclick="removeFeature(this)" class="text-red-500 hover:text-red-700 font-bold px-2">&times;</button>
        `;
        container.appendChild(div);
    }

    function removeFeature(btn) {
        if(document.querySelectorAll('#features-container > div').length > 1) {
            btn.parentElement.remove();
        } else {
             btn.parentElement.querySelector('input').value = '';
        }
    }
</script>
@endsection
