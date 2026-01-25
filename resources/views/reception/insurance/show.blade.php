@extends('layouts.dashboard')

@section('header', 'Claim Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Claim #{{ $claim->claim_number }}</h1>
             <p class="text-sm text-slate-500">Submitted on {{ $claim->submitted_at->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
             @if($claim->status === 'approved' || $claim->status === 'paid')
                <span class="px-3 py-1 text-sm font-bold rounded-full bg-green-100 text-green-800 uppercase tracking-wide border border-green-200">
                    {{ ucfirst($claim->status) }}
                </span>
            @elseif($claim->status === 'rejected')
                <span class="px-3 py-1 text-sm font-bold rounded-full bg-red-100 text-red-800 uppercase tracking-wide border border-red-200">
                    Rejected
                </span>
            @else
                <span class="px-3 py-1 text-sm font-bold rounded-full bg-yellow-100 text-yellow-800 uppercase tracking-wide border border-yellow-200">
                    Pending Review
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Main Info --}}
        <div class="md:col-span-2 space-y-6">
             {{-- Patient & Invoice --}}
             <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                 <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Claim Information</h3>
                 <dl class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm">
                     <dt class="text-slate-500">Patient:</dt>
                     <dd class="font-bold text-slate-900">{{ $claim->patient->name }}</dd>

                     <dt class="text-slate-500">Provider:</dt>
                     <dd class="font-bold text-slate-900">{{ $claim->provider_name }}</dd>

                     <dt class="text-slate-500">Amount Claimed:</dt>
                     <dd class="font-bold text-slate-900">${{ number_format($claim->amount_claimed, 2) }}</dd>
                    
                     <dt class="text-slate-500">Linked Invoice:</dt>
                     <dd class="font-medium text-indigo-600">
                         <a href="{{ route('reception.invoices.print', $claim->invoice_id) }}" target="_blank" class="hover:underline">
                             #INV-{{ str_pad($claim->invoice_id, 6, '0', STR_PAD_LEFT) }}
                         </a>
                     </dd>

                     @if($claim->amount_approved)
                        <dt class="text-slate-500">Confirmed Amount:</dt>
                        <dd class="font-bold text-emerald-600">${{ number_format($claim->amount_approved, 2) }}</dd>
                     @endif
                 </dl>

                  @if($claim->notes)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Internal Notes</span>
                        <p class="text-slate-600 text-sm">{{ $claim->notes }}</p>
                    </div>
                 @endif

                 @if($claim->rejection_reason)
                    <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-md">
                        <span class="block text-xs font-bold text-red-800 uppercase mb-1">Rejection Reason</span>
                        <p class="text-red-700 text-sm font-medium">{{ $claim->rejection_reason }}</p>
                    </div>
                 @endif
             </div>
        </div>

        {{-- Administration / Action Panel --}}
        <div class="md:col-span-1">
            <div class="bg-slate-50 rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-4">Update Status</h3>
                
                <form action="{{ route('reception.insurance.update', $claim) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">New Status</label>
                        <select name="status" class="w-full text-sm rounded-md border-slate-300 shadow-sm" x-data="{ status: '{{ $claim->status }}' }" x-model="status" @change="$dispatch('status-change', status)">
                            <option value="pending" {{ $claim->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $claim->status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $claim->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="paid" {{ $claim->status == 'paid' ? 'selected' : '' }}>Paid (Settled)</option>
                        </select>
                    </div>

                    <div x-data="{ show: '{{ $claim->status }}' === 'approved' || '{{ $claim->status }}' === 'paid' }" @status-change.window="show = ['approved', 'paid'].includes($event.detail)">
                         <div x-show="show" style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 mb-1">Approved Amount</label>
                            <input type="number" name="amount_approved" value="{{ $claim->amount_approved ?? $claim->amount_claimed }}" step="0.01" class="w-full text-sm rounded-md border-slate-300">
                         </div>
                    </div>

                    <div x-data="{ show: '{{ $claim->status }}' === 'rejected' }" @status-change.window="show = ($event.detail === 'rejected')">
                        <div x-show="show" style="display: none;">
                            <label class="block text-xs font-bold text-slate-500 mb-1">Rejection Reason</label>
                            <textarea name="rejection_reason" rows="2" class="w-full text-sm rounded-md border-slate-300">{{ $claim->rejection_reason }}</textarea>
                        </div>
                    </div>
                    
                    <div x-data="{ show: '{{ $claim->status }}' !== 'pending' }" @status-change.window="show = ($event.detail !== 'pending')">
                        <div x-show="show" class="mt-2" style="display: none;">
                           <label class="block text-xs font-bold text-slate-500 mb-1">Response Date</label>
                           <input type="date" name="responded_at" value="{{ $claim->responded_at ? $claim->responded_at->format('Y-m-d') : date('Y-m-d') }}" class="w-full text-sm rounded-md border-slate-300">
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-4 bg-indigo-600 text-white font-bold py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm text-sm">
                        Update Claim
                    </button>
                    
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
