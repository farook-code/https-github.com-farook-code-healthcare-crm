@foreach($messages as $msg)
    @php
        $isMe = $msg->sender_id === auth()->id();
    @endphp
    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} group">
        <div class="chat-bubble {{ $isMe ? 'chat-bubble-me' : 'chat-bubble-them' }}">
            @if($msg->type === 'image')
                <div class="mb-2">
                    <img src="{{ asset($msg->attachment_path) }}" alt="Attachment" class="rounded-lg max-w-full h-auto cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">
                </div>
            @elseif($msg->type === 'file')
                <div class="mb-2 flex items-center bg-gray-50 p-2 rounded border border-gray-200">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    <a href="{{ asset($msg->attachment_path) }}" target="_blank" class="text-indigo-600 underline text-sm ml-2 truncate max-w-[150px]">
                        Download File
                    </a>
                </div>
            @endif
            
            @if($msg->message)
                <p>{{ $msg->message }}</p>
            @endif
            
            <span class="text-[10px] opacity-70 mt-1 block {{ $isMe ? 'text-indigo-100' : 'text-gray-400' }}">
                {{ $msg->created_at->format('h:i A') }}
            </span>
        </div>
    </div>
@endforeach
