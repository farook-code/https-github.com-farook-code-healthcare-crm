@extends('layouts.dashboard')

@section('header', 'System Audit Logs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700">Recent Activity</h3>
            <span class="text-xs text-slate-400">Security & Compliance</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs uppercase mr-3">
                                        {{ substr($log->user->name ?? 'System', 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">{{ $log->user->name ?? 'System' }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->user->role->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $log->action === 'LOGIN' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $log->action === 'LOGOUT' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $log->action === 'CREATE' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $log->action === 'DELETE' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-800' }}
                                ">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-600 max-w-sm truncate" title="{{ $log->details }}">
                                {{ $log->details }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm text-slate-500 font-mono">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-right text-sm text-slate-500">
                                {{ $log->created_at->format('M d H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                No audit logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
