@extends('layouts.dashboard')

@section('header', 'Inbox')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-4 flex justify-end">
        <a href="{{ route('messages.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Compose Message
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($messages as $message)
                <li>
                    <a href="{{ route('messages.show', $message) }}" class="block hover:bg-gray-50 {{ $message->is_read ? '' : 'bg-blue-50' }}">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-indigo-600 truncate">
                                    {{ $message->subject ?? 'No Subject' }}
                                </p>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="text-xs text-gray-500 mr-2">From:</p>
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $message->sender->name }}
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-500">
                                        {{ Str::limit($message->body, 60) }}
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                    <p>
                                        {{ $message->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="px-4 py-10 text-center text-gray-500">
                    No messages in your inbox.
                </li>
            @endforelse
        </ul>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
