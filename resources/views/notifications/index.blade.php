@extends('layouts.dashboard')

@section('header', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">All Notifications</h1>
        
        @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm text-sm font-medium">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md border border-gray-200">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <li class="{{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @php $type = $notification->data['type'] ?? 'info'; @endphp
                                    @if($type == 'appointment')
                                         <span class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                             <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                         </span>
                                    @elseif($type == 'lab')
                                         <span class="h-10 w-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                         </span>
                                    @elseif($type == 'warning' || $type == 'stock')
                                         <span class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                         </span>
                                     @else
                                         <span class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                         </span>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-indigo-600 truncate">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        {{ $notification->data['message'] ?? '' }}
                                    </div>
                                </div>
                            </div>
                            <div class="ml-2 flex-shrink-0 flex flex-col items-end">
                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $notification->read_at ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $notification->read_at ? 'Read' : 'New' }}
                                </p>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-2 flex justify-end">
                             <a href="{{ route('notifications.read', $notification->id) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900">View Details &rarr;</a>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-4 py-8 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2 text-sm font-medium text-gray-900">No notifications found</p>
                </li>
            @endforelse
        </ul>
        @if(method_exists($notifications, 'links'))
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
