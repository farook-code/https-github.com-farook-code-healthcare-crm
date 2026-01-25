@extends('layouts.dashboard')

@section('header', 'Insurance Claims')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Stats Row (Simplified) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Pending</p>
            <p class="text-2xl font-bold text-slate-800">{{ $claims->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Approved</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $claims->where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Rejected</p>
            <p class="text-2xl font-bold text-red-600">{{ $claims->where('status', 'rejected')->count() }}</p>
        </div>
         <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Total Value</p>
            <p class="text-2xl font-bold text-indigo-600">${{ number_format($claims->sum('amount_claimed'), 2) }}</p>
        </div>
    </div>

    {{-- Claims Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Claim #</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($claims as $claim)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                            {{ $claim->claim_number }}
                            <div class="text-xs text-slate-400">Inv #{{ $claim->invoice_id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                            {{ $claim->patient->name }}
                        </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $claim->provider_name }}
                             <div class="text-xs text-slate-400">{{ $claim->submitted_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($claim->status === 'approved' || $claim->status === 'paid')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($claim->status) }}
                                </span>
                            @elseif($claim->status === 'rejected')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-slate-900">
                            ${{ number_format($claim->amount_claimed, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('reception.insurance.show', $claim) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            No insurance claims found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $claims->links() }}
        </div>
    </div>
</div>
@endsection
