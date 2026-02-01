@extends('layouts.dashboard')
@section('header', 'Manage Patients')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    {{-- Search Bar --}}
    <div class="mb-6 flex justify-between items-center">
        <form method="GET" action="{{ route('reception.patients.index') }}" class="w-full max-w-lg">
            <div class="relative rounded-md shadow-sm">
                <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-12 sm:text-sm border-gray-300 rounded-md py-2" placeholder="Search by name, phone, email...">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </form>
        <a href="{{ route('reception.patients.create') }}" class="ml-4 mr-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            + New Patient
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name/ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Insurance</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($patients as $patient)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500 font-bold">
                                        {{ substr($patient->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('reception.patients.show', $patient) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                        {{ $patient->name }}
                                    </a>
                                    <div class="text-sm text-gray-500">
                                        {{ $patient->patient_code }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $patient->phone }}</div>
                            <div class="text-sm text-gray-500">{{ $patient->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $patient->insurance_provider ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $patient->policy_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('reception.patients.edit', $patient) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                            <a href="{{ route('reception.appointments.create', ['patient_id' => $patient->id]) }}" class="text-green-600 hover:text-green-900">Book Appt</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                         <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                             No patients found.
                         </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($patients->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $patients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
