@extends('layouts.dashboard')

@section('header', 'Messages')

@section('content')
<div class="max-w-7xl mx-auto h-[calc(100vh-140px)] px-4 sm:px-6 lg:px-8 py-6">
    <div class="chat-container flex font-sans h-full">
        {{-- Chat List --}}
        @include('chat.partials.chat_list')

        {{-- Empty State --}}
        <div class="flex-1 bg-gray-50 flex flex-col justify-center items-center text-center p-8 rounded-r-2xl">
            <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mb-6 animate-pulse">
                 <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                 </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Welcome to Messages</h3>
            <p class="text-gray-500 max-w-sm mb-8">Select a conversation from the sidebar to start chatting, or search for a user to begin.</p>
        </div>
    </div>
</div>
@endsection
