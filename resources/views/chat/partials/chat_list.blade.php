<div class="md:w-1/3 bg-gray-50 border-r border-gray-100 flex flex-col h-full {{ isset($chat) ? 'hidden md:flex' : 'flex w-full' }}">
    {{-- Header --}}
    <div class="px-6 py-4 bg-white border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
        <h2 class="text-xl font-bold text-gray-800">Messages</h2>
        <button class="bg-indigo-50 hover:bg-indigo-100 text-indigo-600 p-2 rounded-full transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
        </button>
    </div>

    {{-- Search (Visual) --}}
    <div class="px-6 py-3 relative">
        <div class="relative text-gray-400 focus-within:text-gray-600">
             <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                 <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                 </svg>
             </div>
             <input type="text" id="user-search" placeholder="Search people..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
        </div>
        <div id="search-results" class="absolute left-6 right-6 top-14 bg-white shadow-xl rounded-xl border border-gray-100 z-50 hidden divide-y divide-gray-100 overflow-hidden">
            {{-- Results --}}
        </div>
    </div>

    {{-- List --}}
    <div class="flex-1 overflow-y-auto space-y-0.5 p-3 scrollbar-thin scrollbar-thumb-gray-200">
        @foreach($chats as $c)
            @php
                $otherUser = $c->user_one_id == auth()->id() ? $c->userTwo : $c->userOne;
                if(!$otherUser) continue;
                $isActive = isset($chat) && $chat->id === $c->id;
            @endphp
            <a href="{{ route('chat.open', $otherUser->id) }}"
               class="flex items-center gap-4 p-3 rounded-xl transition-all duration-200 {{ $isActive ? 'bg-indigo-50 border-l-4 border-indigo-600 shadow-sm' : 'hover:bg-gray-100 border-l-4 border-transparent' }}">
                
                <div class="relative shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&color=7F9CF5&background=EBF4FF" 
                         alt="{{ $otherUser->name }}" 
                         class="w-12 h-12 rounded-full object-cover ring-2 {{ $isActive ? 'ring-indigo-200' : 'ring-transparent' }}">
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                        <h3 class="text-sm font-semibold {{ $isActive ? 'text-indigo-900' : 'text-gray-900' }} truncate">
                            {{ $otherUser->name }}
                        </h3>
                        {{-- Random time for now, or real if we had last message time --}}
                        <div class="flex flex-col items-end">
                             <span class="text-xs text-gray-400">Now</span>
                             @if($c->unreadMessages->count() > 0)
                                <span class="bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full mt-1">
                                    {{ $c->unreadMessages->count() }}
                                </span>
                             @endif
                        </div>
                    </div>
                    <p class="text-xs {{ $isActive ? 'text-indigo-700 font-medium' : 'text-gray-500' }} truncate">
                        Click to view conversation
                    </p>
                </div>
            </a>
        @endforeach
    </div>
</div>
