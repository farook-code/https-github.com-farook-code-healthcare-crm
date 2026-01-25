@extends('layouts.dashboard')

@section('header', 'My Patients')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Patient List
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Patients you have previously consulted with.
            </p>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($patients as $patient)
                <li class="px-6 py-4 hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold">
                                {{ substr($patient->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
                                <div class="text-sm text-gray-500">{{ $patient->email }}</div>
                                <div class="text-xs text-gray-400 mt-1">Code: {{ $patient->patient_code ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="flex space-x-4">
                            @if(auth()->id() !== $patient->user_id)
                                <a href="{{ route('chat.open', $patient->user_id) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Message
                                </a>
                            @endif
                            <a href="{{ route('lab-reports.index', $patient) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Medical Records
                            </a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-10 text-center text-gray-500 italic">
                    You haven't seen any patients yet.
                </li>
            @endforelse
        </ul>
        @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $patients->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
