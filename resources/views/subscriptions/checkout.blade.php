<x-dashboard-layout>
    @section('header', 'Secure Checkout')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-12">
                    <div class="flex flex-col md:flex-row gap-12">
                        <!-- Order Summary -->
                        <div class="md:w-1/3">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 font-heading">
                                Order Summary
                            </h3>
                            <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-6 space-y-4">
                                <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-600 dark:text-gray-300">{{ $plan->name }} Plan</span>
                                    <span class="font-bold text-gray-900 dark:text-gray-100">${{ $plan->price }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Duration</span>
                                    <span class="text-gray-700 dark:text-gray-200">{{ $plan->duration_in_days }} Days</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Total</span>
                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ $plan->price }}</span>
                                </div>
                                <div class="mt-4">
                                     <ul class="space-y-2">
                                        @foreach($plan->features as $feature)
                                            <li class="flex items-start text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="h-4 w-4 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form (Mock) -->
                        <div class="md:w-2/3">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 font-heading">
                                Payment Details
                            </h3>
                            
                            <form action="{{ route('subscriptions.store') }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cardholder Name</label>
                                        <input type="text" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="John Doe" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card Number</label>
                                        <div class="relative">
                                            <input type="text" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-10" placeholder="0000 0000 0000 0000" required>
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiration Date</label>
                                            <input type="text" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="MM/YY" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CVC</label>
                                            <input type="text" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="123" required>
                                        </div>
                                    </div>

                                    <div class="pt-6">
                                        <button type="button" onclick="confirmPayment()" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            Pay ${{ $plan->price }} & Subscribe
                                        </button>
                                        <p class="mt-4 text-center text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                            Payments are secure and encrypted. This is a secure 256-bit SSL encrypted payment.
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mock Payment Loader -->
    <div id="payment-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-slate-800 p-8 rounded-xl shadow-2xl flex flex-col items-center max-w-sm mx-4">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-indigo-600 mb-4"></div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white text-center">Processing Payment...</h3>
            <p class="text-gray-500 dark:text-gray-400 text-center mt-2">Please do not close this window.</p>
        </div>
    </div>

    <script>
        function confirmPayment() {
            // Mock validation
            const inputs = document.querySelectorAll('input');
            let valid = true;
            inputs.forEach(input => {
                if(!input.value) {
                    input.classList.add('border-red-500');
                    valid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if(!valid) return;

            // Show loader
            document.getElementById('payment-overlay').classList.remove('hidden');

            // Simulate delay then submit
            setTimeout(() => {
                document.getElementById('checkout-form').submit();
            }, 2000);
        }
    </script>
</x-dashboard-layout>
