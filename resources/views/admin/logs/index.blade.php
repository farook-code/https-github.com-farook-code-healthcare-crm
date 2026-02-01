@extends('layouts.dashboard')

@section('header', 'Security Center')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Activities (24h)</h4>
            <div class="flex items-center justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ \App\Models\AuditLog::where('created_at', '>=', now()->subDay())->count() }}</span>
                <span class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Unique Users (24h)</h4>
             <div class="flex items-center justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ \App\Models\AuditLog::where('created_at', '>=', now()->subDay())->distinct('user_id')->count() }}</span>
                 <span class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </span>
            </div>
        </div>
         <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Critical Actions</h4>
             <div class="flex items-center justify-between">
                <span class="text-3xl font-extrabold text-slate-900">{{ \App\Models\AuditLog::whereIn('action', ['deleted', 'destroyed'])->where('created_at', '>=', now()->subDay())->count() }}</span>
                 <span class="p-2 bg-red-50 rounded-lg text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        {{-- Filter Header --}}
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Security Timeline</h3>
                <p class="text-sm text-slate-500">Real-time trail of all system activities.</p>
            </div>
            
            <form method="GET" class="flex flex-wrap gap-2 items-center">
                <select name="user_id" class="text-sm border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
                
                <select name="action" class="text-sm border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 uppercase">
                    <option value="">All Actions</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
                
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition shadow-md">
                    Filter
                </button>
                 @if(request()->anyFilled(['user_id', 'action']))
                    <a href="{{ route('admin.logs.index') }}" class="text-sm text-slate-500 hover:text-slate-800 underline ml-2">Clear</a>
                @endif
            </form>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-1/3">Details</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Device & IP</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Time</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50 transition duration-150">
                            {{-- User --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs uppercase shadow-sm">
                                        {{ substr($log->user->name ?? 'Sys', 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-slate-900">{{ $log->user->name ?? 'System' }}</div>
                                        <div class="text-xs text-slate-500">{{ $log->user->role->name ?? 'Automated' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- Action --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colors = [
                                        'created' => 'bg-green-100 text-green-800 ring-green-600/20',
                                        'updated' => 'bg-blue-100 text-blue-800 ring-blue-600/20',
                                        'deleted' => 'bg-red-100 text-red-800 ring-red-600/20',
                                        'login' => 'bg-purple-100 text-purple-800 ring-purple-600/20',
                                    ];
                                    $color = $colors[$log->action] ?? 'bg-slate-100 text-slate-800 ring-slate-600/20';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full ring-1 ring-inset {{ $color }} uppercase">
                                    {{ $log->action }}
                                </span>
                            </td>

                            {{-- Details --}}
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-800 font-medium break-words">
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                </div>
                                
                                @if($log->changes)
                                <div class="mt-2">
                                    <details class="group">
                                        <summary class="list-none text-xs text-indigo-600 cursor-pointer font-semibold flex items-center gap-1">
                                            <span>View Changes</span>
                                            <svg class="w-3 h-3 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </summary>
                                        <div class="mt-2 bg-slate-50 p-3 rounded border border-slate-200 text-xs font-mono overflow-x-auto max-w-md">
                                            @foreach($log->changes as $key => $values)
                                                @if(is_array($values))
                                                   <div class="mb-1">
                                                       <strong>{{ $key }}:</strong> 
                                                       <span class="text-red-600 line-through">{{ is_array($values['old'] ?? null) ? json_encode($values['old']) : ($values['old'] ?? 'null') }}</span>
                                                       <span class="text-slate-400 mx-1">→</span>
                                                       <span class="text-green-600">{{ is_array($values['new'] ?? null) ? json_encode($values['new']) : ($values['new'] ?? 'null') }}</span>
                                                   </div>
                                                @else
                                                    {{-- Fallback format if simple array --}}
                                                    <div>{{ $key }}: {{ is_array($values) ? json_encode($values) : $values }}</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </details>
                                </div>
                                @endif
                            </td>

                            {{-- Device & IP --}}
                             <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs text-slate-900 font-mono bg-slate-100 px-2 py-1 rounded inline-block mb-1">
                                    {{ $log->ip_address ?? 'Unknown IP' }}
                                </div>
                                <div class="text-xs text-slate-500 max-w-[150px] truncate" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent ?? '-' }}
                                </div>
                            </td>

                            {{-- Time --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-bold text-slate-700">{{ $log->created_at->format('H:i') }}</div>
                                <div class="text-xs text-slate-500">{{ $log->created_at->format('M d, Y') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-3 bg-slate-50 rounded-full mb-3">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-slate-900 font-medium">No audit logs found</h3>
                                    <p class="text-slate-500 text-sm mt-1">System activity will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
