@extends('layouts.dashboard')

@section('header', 'My Prescriptions')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">

    {{-- Header with Back Button --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Medications & Prescriptions</h1>
            <p class="text-sm text-slate-500">View your active prescriptions and request refills.</p>
        </div>
        <a href="{{ route('patient.dashboard') }}" class="group flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
            <div class="mr-2 p-1 rounded-md group-hover:bg-indigo-50 transition">
                <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </div>
            Back to Dashboard
        </a>
    </div>

    {{-- Content --}}
    <div class="bg-white shadow-sm border border-slate-200 rounded-xl overflow-hidden">
        @if($prescriptions->isEmpty())
            <div class="p-12 text-center text-slate-500">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <h3 class="text-lg font-medium text-slate-900">No Prescriptions Found</h3>
                <p class="mt-1">You don't have any prescription records yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Medicine</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Dosage & Frequency</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Prescribed By</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($prescriptions as $rx)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $rx->medicine_name }}</div>
                                    @if($rx->instructions)
                                        <div class="text-xs text-slate-500 mt-1 italic">"{{ $rx->instructions }}"</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ $rx->dosage }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $rx->duration }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500 mr-2">
                                            {{ substr($rx->diagnosis->appointment->doctor->name ?? 'D', 0, 1) }}
                                        </div>
                                        <div class="text-sm">
                                            <div class="font-medium text-slate-900">{{ $rx->diagnosis->appointment->doctor->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-slate-500">{{ $rx->diagnosis->appointment->department->name ?? 'General' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500">
                                    {{ $rx->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('patient.prescriptions.refill', $rx->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 border border-indigo-200 hover:bg-indigo-50 rounded px-3 py-1.5 transition">
                                            Request Refill
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($prescriptions->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $prescriptions->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
