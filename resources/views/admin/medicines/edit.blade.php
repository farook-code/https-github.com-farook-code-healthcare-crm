@extends('layouts.dashboard')

@section('header', 'Edit Medicine')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.medicines.update', $medicine->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                {{-- Name & Generic --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Medicine Name</label>
                        <input type="text" name="name" value="{{ $medicine->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Generic Name</label>
                        <input type="text" name="generic_name" value="{{ $medicine->generic_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                {{-- SKU & Manufacturer --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SKU (Code)</label>
                        <input type="text" name="sku" value="{{ $medicine->sku }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Manufacturer</label>
                        <input type="text" name="manufacturer" value="{{ $medicine->manufacturer }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                {{-- Price & Stock --}}
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price ($)</label>
                        <input type="number" step="0.01" name="price" value="{{ $medicine->price }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="{{ $medicine->stock_quantity }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit</label>
                        <select name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="tablet" {{ $medicine->unit == 'tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="bottle" {{ $medicine->unit == 'bottle' ? 'selected' : '' }}>Bottle</option>
                            <option value="strip" {{ $medicine->unit == 'strip' ? 'selected' : '' }}>Strip</option>
                            <option value="injection" {{ $medicine->unit == 'injection' ? 'selected' : '' }}>Injection</option>
                            <option value="tube" {{ $medicine->unit == 'tube' ? 'selected' : '' }}>Tube</option>
                        </select>
                    </div>
                </div>

                {{-- Expiry --}}
                <div>
                     <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                     <input type="date" name="expiry_date" value="{{ $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $medicine->description }}</textarea>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.medicines.index') }}" class="mr-4 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Update Medicine</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
