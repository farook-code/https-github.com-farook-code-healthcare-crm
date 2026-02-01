@extends('layouts.dashboard')

@section('header', 'Patient Policies')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Patient Insurance Policies</h1>
            <p class="mt-2 text-sm text-gray-700">View active insurance coverages for patients.</p>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Patient</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Provider</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Policy Number</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Coverage Limit</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Validity</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($policies as $policy)
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $policy->patient->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $policy->provider->name }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">{{ $policy->policy_number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${{ number_format($policy->coverage_limit, 2) }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    {{ $policy->start_date ? $policy->start_date->format('M Y') : 'N/A' }} - 
                                    {{ $policy->end_date ? $policy->end_date->format('M Y') : 'Lifetime' }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm">
                                     <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ ucfirst($policy->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                 <div class="mt-4">
                    {{ $policies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
