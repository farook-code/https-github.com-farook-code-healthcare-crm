@extends('layouts.dashboard')

@section('header', 'All Lab Reports')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    
    {{-- Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Total Reports</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\LabReport::count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">This Month</p>
            <p class="text-2xl font-bold text-indigo-600">{{ \App\Models\LabReport::whereMonth('created_at', now()->month)->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Pending Review</p>
            <p class="text-2xl font-bold text-orange-600">{{ \App\Models\LabReport::where('status', 'pending')->count() }}</p>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-slate-200">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Master Department Records
                </h3>
                <a href="{{ route('lab-reports.quick-upload') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Scan & Upload
                </a>
            </div>
            
            <form method="GET" action="{{ url()->current() }}" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" title="From Date">
                    <span class="text-gray-400">-</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-xs border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" title="To Date">
                </div>
                <div class="relative rounded-md shadow-sm flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" class="text-xs focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 border-gray-300 rounded-md" placeholder="Search patient, report...">
                </div>
                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'date_from', 'date_to']))
                    <a href="{{ url()->current() }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Info</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded By</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $report->title }}</div>
                                <div class="text-xs text-gray-500">{{ $report->created_at->format('M d, Y H:i') }}</div>
                                <div class="text-xs text-indigo-500 uppercase font-bold mt-1">{{ $report->file_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                        {{ substr($report->patient->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $report->patient->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $report->patient->id ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $report->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($report->status ?? 'Shared') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->uploader->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('lab-reports.download', $report->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md">
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No lab reports found in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
