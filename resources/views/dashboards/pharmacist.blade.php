@extends('layouts.dashboard')

@section('header', 'Pharmacy Dashboard')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        
        <!-- Inventory Management -->
        <a href="{{ route('admin.medicines.index') }}" class="block group">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3 group-hover:bg-blue-600 transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Medicine Inventory</h3>
                        </div>
                        <p class="text-sm text-gray-500">Manage stock, prices, and medicine details.</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- New Sale -->
        <a href="{{ route('reception.invoices.create', ['category' => 'pharmacy']) }}" class="block group">
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3 group-hover:bg-green-600 transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">New Sale / Billing</h3>
                        </div>
                        <p class="text-sm text-gray-500">Create new pharmacy invoice for patients.</p>
                    </div>
                </div>
            </div>
        </a>

        <!-- Invoice History -->
        <a href="{{ route('reception.invoices.index') }}" class="block group">
             <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 h-full">
                <div class="px-4 py-5 sm:p-6 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3 group-hover:bg-purple-600 transition-colors">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-900">Invoice History</h3>
                        </div>
                        <p class="text-sm text-gray-500">View past sales and payments.</p>
                    </div>
                </div>
            </div>
        </a>

    </div>

    <!-- Low Stock Alerts -->
    @if(isset($lowStockMedicines) && $lowStockMedicines->count() > 0)
    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-red-600 flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Low Stock Alerts
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                The following medicines are running low on stock.
            </p>
        </div>
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($lowStockMedicines as $med)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-indigo-600 truncate">{{ $med->name }}</p>
                        <div class="ml-2 flex-shrink-0 flex">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Left: {{ $med->stock_quantity }}
                            </span>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="bg-gray-50 px-4 py-4 sm:px-6">
             <a href="{{ route('admin.medicines.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all inventory &rarr;</a>
        </div>
    </div>
    @endif
</div>
@endsection
