@extends('layouts.dashboard')

@section('header', 'Secure Payment Gateway')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-10 justify-center">
        
        <!-- Invoice Summary -->
        <div class="lg:w-1/3">
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 sticky top-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Consultation Fee</span>
                    <span class="font-medium">${{ number_format($invoice->amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Patient</span>
                    <span class="font-medium">{{ optional($invoice->patient)->name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-gray-600">Date</span>
                    <span class="font-medium">{{ $invoice->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between py-4 text-xl font-bold text-gray-900">
                    <span>Total</span>
                    <span>${{ number_format($invoice->amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="lg:w-1/2">
            <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Pay with Card</h2>
                    <div class="flex space-x-2">
                        <div class="h-8 w-12 bg-blue-600 rounded"></div>
                        <div class="h-8 w-12 bg-orange-500 rounded"></div>
                    </div>
                </div>

                <form action="{{ route('reception.invoices.process', $invoice) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="card_holder" class="block text-sm font-medium text-gray-700">Card Holder Name</label>
                        <input type="text" name="card_holder" id="card_holder" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="John Doe">
                    </div>

                    <div>
                        <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="card_number" id="card_number" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="0000 0000 0000 0000">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="expiry" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                            <input type="text" name="expiry" id="expiry" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="MM / YY">
                        </div>
                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                            <input type="text" name="cvc" id="cvc" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="123">
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Pay ${{ number_format($invoice->amount, 2) }}
                    </button>
                    
                    <p class="text-center text-xs text-xs text-gray-500 flex justify-center items-center">
                        <svg class="h-4 w-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        Payments are secure and encrypted.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
