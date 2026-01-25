@extends('layouts.dashboard')

@section('header', 'Refill Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Medicine</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase">Requested</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @forelse($requests as $req)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-900">{{ $req->diagnosis->patient->name }}</div>
                            <div class="text-xs text-slate-500">Dr. {{ $req->diagnosis->doctor->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-indigo-600 font-medium">{{ $req->medicine_name }}</div>
                            <div class="text-xs text-slate-500">{{ $req->dosage }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                            {{ $req->refill_requested_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('doctor.refills.update', $req) }}" method="POST" class="inline-block">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button class="text-green-600 hover:text-green-900 font-bold mr-3">Approve</button>
                            </form>
                             <form action="{{ route('doctor.refills.update', $req) }}" method="POST" class="inline-block">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="denied">
                                <button class="text-red-500 hover:text-red-700 font-bold">Deny</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-500">No pending refill requests.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
