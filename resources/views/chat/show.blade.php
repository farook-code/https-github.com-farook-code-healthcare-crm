@extends('layouts.dashboard')

@section('header', 'Chat')

@section('content')
<div class="max-w-7xl mx-auto h-[calc(100vh-140px)] px-4 sm:px-6 lg:px-8 py-6 mb-16 md:mb-0">
    <div class="chat-container flex font-sans h-full">
        {{-- Chat List --}}
        @include('chat.partials.chat_list')

        {{-- Main Chat Area --}}
        <div class="flex-1 flex flex-col bg-white relative rounded-r-2xl overflow-hidden">
            
            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white/90 backdrop-blur-sm z-10 sticky top-0 shadow-sm">
                @php
                    $otherUser = (isset($chat) && $chat->user_one_id == auth()->id()) ? $chat->userTwo : ($chat->userOne ?? null);
                @endphp
                <div class="flex items-center gap-4">
                     {{-- Mobile Back Button --}}
                    <a href="{{ route('chat.index') }}" class="md:hidden text-gray-500 hover:text-indigo-600 mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>

                    <div class="relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name ?? 'Deleted User') }}&color=7F9CF5&background=EBF4FF" 
                             class="w-10 h-10 rounded-full ring-2 ring-gray-100 object-cover">
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <h3 class="text-gray-900 font-bold text-sm">{{ $otherUser->name ?? 'Deleted User' }}</h3>
                        <div class="flex items-center gap-1.5 ">
                            <span class="text-xs text-green-600 font-medium">Online</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-1">
                    <button class="text-gray-400 hover:text-indigo-600 p-2 rounded-xl hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    <button class="text-gray-400 hover:text-indigo-600 p-2 rounded-xl hover:bg-indigo-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Messages --}}
            <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50 scrollbar-thin scrollbar-thumb-gray-200" style="scroll-behavior: smooth;">
                @include('chat.partials.messages')
            </div>

            {{-- Input Area --}}
            <div class="p-4 bg-white border-t border-gray-100">
                 <form id="chat-form" class="relative flex items-end gap-2">
                    @csrf
                    <input type="hidden" id="chat_id" value="{{ $chat->id }}">
                    
                    <input type="file" id="attachment" name="attachment" class="hidden" onchange="document.getElementById('file-indicator').classList.remove('hidden')">
                    
                    <label for="attachment" class="p-3 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition-colors mb-0.5 cursor-pointer relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        <span id="file-indicator" class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full hidden"></span>
                    </label>

                    <input type="text" id="message" 
                           class="flex-1 py-3.5 px-5 rounded-3xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 placeholder-gray-400 text-sm text-gray-800 transition-all shadow-sm"
                           placeholder="Type your message..." 
                           autocomplete="off">
                           
                    <button type="submit" class="p-3.5 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 shadow-lg shadow-indigo-200 transform hover:scale-105 active:scale-95 transition-all mb-0.5">
                        <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                 </form>
            </div>

        </div>
    </div>
</div>
@endsection
