@extends('layouts.dashboard')

@section('header', 'My Admissions (IPD)')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    
    <div class="bg-white shadow overflow-hidden sm:rounded-md border border-gray-200">
        <ul class="divide-y divide-gray-200">
            @forelse($admissions as $admission)
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-indigo-600 truncate">
                                {{ $admission->bed->ward->name ?? 'Ward' }} - Bed {{ $admission->bed->bed_number ?? 'N/A' }}
                            </p>
                            <div class="ml-2 flex-shrink-0 flex">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $admission->status === 'discharged' ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($admission->status) }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 sm:flex sm:justify-between">
                            <div class="sm:flex">
                                <p class="flex items-center text-sm text-gray-500">
                                    🏥 Admitted: {{ $admission->admission_date->format('M d, Y h:i A') }}
                                </p>
                                @if($admission->discharge_date)
                                    <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                        🏠 Discharged: {{ $admission->discharge_date->format('M d, Y h:i A') }}
                                    </p>
                                @endif
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                <span class="mr-2">Dr. {{ $admission->doctor->name ?? 'Unknown' }}</span>
                            </div>
                        </div>
                        @if($admission->reason_for_admission)
                            <div class="mt-2 text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                <span class="font-medium">Reason:</span> {{ $admission->reason_for_admission }}
                            </div>
                        @endif
                    </div>
                </li>
            @empty
                <li class="px-4 py-10 text-center text-gray-500">
                    No admission records found.
                </li>
            @endforelse
        </ul>
        @if($admissions->hasPages())
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                {{ $admissions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
