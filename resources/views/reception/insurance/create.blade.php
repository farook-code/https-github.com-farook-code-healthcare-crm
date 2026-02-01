@extends('layouts.dashboard')

@section('header', 'New Insurance Claim')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 text-lg">Submit Claim for Invoice #{{ $invoice->id }}</h3>
            <span class="text-sm font-bold text-slate-500">Total: ${{ number_format($invoice->amount, 2) }}</span>
        </div>
        
        <form action="{{ route('reception.insurance.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

            {{-- Patient Info (Read Only) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Patient Name</label>
                    <input type="text" value="{{ $invoice->patient->name }}" readonly class="w-full bg-slate-50 border-slate-200 rounded-md text-slate-600 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Insurance Provider</label>
                    <input type="text" id="provider_name" name="provider_name" value="{{ $invoice->patient->insurance_provider ?? '' }}" required class="w-full border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="e.g. Aetna">
                </div>
                 <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Policy Number</label>
                    <div class="flex gap-2">
                        <input type="text" id="policy_number" name="policy_number" value="{{ $invoice->patient->policy_number ?? '' }}" required class="w-full border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Policy #">
                        <button type="button" id="btn-verify" class="bg-teal-600 text-white px-3 py-2 rounded-md font-bold text-xs hover:bg-teal-700 transition">Verify</button>
                    </div>
                    <p id="verify-msg" class="text-xs mt-1 hidden"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Claim Number</label>
                    <input type="text" name="claim_number" value="CLM-{{ date('Ymd') }}-{{ $invoice->id }}" required class="w-full border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono">
                </div>
                 <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Date Submitted</label>
                    <input type="date" name="submitted_at" value="{{ date('Y-m-d') }}" required class="w-full border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Amount Claimed</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                      <span class="text-gray-500 sm:text-sm">$</span>
                    </div>
                    <input type="number" name="amount_claimed" value="{{ $invoice->amount }}" step="0.01" required class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00">
                </div>
                 <p class="mt-1 text-xs text-slate-400">Usually matches the total invoice amount.</p>
            </div>

            <div>
                 <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Notes / Remarks</label>
                 <textarea name="notes" rows="3" class="w-full border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="{{ route('reception.invoices.checkout', $invoice) }}" class="px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-bold hover:bg-slate-50 text-sm">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 shadow-sm text-sm">Submit Claim</button>
            </div>
        </form>
    </div>
</div>
@section('scripts')
<script>
    document.getElementById('btn-verify').addEventListener('click', function() {
        const provider = document.getElementById('provider_name').value;
        const policy = document.getElementById('policy_number').value;
        const msg = document.getElementById('verify-msg');
        
        msg.classList.add('hidden');
        
        if(!provider || !policy) {
            alert('Please enter provider and policy number');
            return;
        }
        
        const originalText = this.innerText;
        this.innerText = 'Checking...';
        this.disabled = true;
        
        fetch("{{ route('reception.insurance.verify') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ provider_name: provider, policy_number: policy })
        })
        .then(r => r.json())
        .then(data => {
            this.innerText = originalText;
            this.disabled = false;
            msg.classList.remove('hidden');
            if(data.status === 'active') {
                msg.innerText = "✅ " + data.message + " (Plan: " + (data.coverage_details?.plan_type || 'N/A') + ")";
                msg.className = "text-xs mt-1 text-green-600 font-bold block";
            } else {
                msg.innerText = "❌ " + data.message;
                msg.className = "text-xs mt-1 text-red-600 font-bold block";
            }
        })
        .catch(err => {
            console.error(err);
            this.innerText = originalText;
            this.disabled = false;
        });
    });
</script>
@endsection
@endsection
