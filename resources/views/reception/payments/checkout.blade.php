@extends('layouts.dashboard')

@section('header', 'Secure Checkout')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Header Summary --}}
        <div class="bg-slate-50 p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Complete Payment
            </h2>
            <p class="text-slate-500 mt-1">Invoice #{{ $invoice->id }} for {{ $invoice->patient->name ?? optional($invoice->appointment)->patient->name ?? 'Patient' }}</p>
        </div>

        <div class="p-8">
            {{-- Order Summary --}}
            <div class="flex justify-between items-end mb-8 border-b border-dashed border-slate-300 pb-6">
                <div>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Total Amount</p>
                    <p class="text-4xl font-black text-slate-900">${{ number_format($invoice->amount, 2) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-slate-500">Date Issued: {{ $invoice->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            {{-- Stripe Elements Placeholder --}}
            <div id="payment-element" class="mb-6 min-h-[200px] bg-slate-50 rounded-lg p-4 border border-slate-200 flex items-center justify-center">
                <!-- Stripe Elements will mount here -->
                <p class="text-slate-400 text-sm animate-pulse">Loading secure payment form...</p>
            </div>
            
            <div id="payment-message" class="hidden mb-4 p-3 rounded-md text-sm font-bold text-center"></div>

            <button id="submit" class="w-full bg-indigo-600 text-white font-bold py-3.5 rounded-lg shadow-md hover:bg-indigo-700 transition transform hover:-translate-y-0.5 flex justify-center items-center">
                <span id="button-text">Pay Now</span>
                <div id="spinner" class="hidden ml-2 w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            </button>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-slate-400 flex items-center justify-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Payments are secure and encrypted.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Stripe JS --}}
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        // Use your public key here (Demo Key)
        const stripe = Stripe('{{ env('STRIPE_KEY', 'pk_test_TYooMQauvdEDq54NiTphI7jx') }}'); 

        const clientSecret = '{{ $intent['client_secret'] }}';
        
        // Mock Mode Handling logic
        if (clientSecret.startsWith('pi_mock_')) {
            document.getElementById('payment-element').innerHTML = 
                '<div class="text-center p-4 text-slate-600 bg-yellow-50 border border-yellow-200 rounded">' +
                '<strong>Mock Payment Mode</strong><br>' +
                'Stripe API keys are not configured. Click "Pay Now" to simulate success.' +
                '</div>';
                
            document.getElementById('submit').addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = "{{ route('reception.payments.success', $invoice) }}";
            });
            return;
        }

        const elements = stripe.elements({ clientSecret });
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        const form = document.getElementById('submit');

        form.addEventListener('click', async (e) => {
            e.preventDefault();
            setLoading(true);

            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: "{{ route('reception.payments.success', $invoice) }}",
                },
            });

            if (error) {
                showMessage(error.message);
                setLoading(false);
            } else {
                // Determine logic handled by return_url
            }
        });

        function showMessage(messageText) {
            const messageContainer = document.querySelector("#payment-message");
            messageContainer.classList.remove("hidden");
            messageContainer.classList.add("bg-red-50", "text-red-600");
            messageContainer.textContent = messageText;
            setTimeout(function () {
                messageContainer.classList.add("hidden");
                messageContainer.textContent = "";
            }, 4000);
        }

        function setLoading(isLoading) {
            if (isLoading) {
                document.querySelector("#submit").disabled = true;
                document.querySelector("#spinner").classList.remove("hidden");
                document.querySelector("#button-text").classList.add("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#spinner").classList.add("hidden");
                document.querySelector("#button-text").classList.remove("hidden");
            }
        }
    });
</script>
@endsection
