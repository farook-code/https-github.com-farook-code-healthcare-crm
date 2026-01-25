@extends('layouts.dashboard')

@section('header', $message->subject)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6 mb-6">
        <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-4">
            <div class="flex items-center">
                 <div class="mr-4">
                    <span class="inline-block h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-gray-500">
                        {{ substr($message->sender->name, 0, 1) }}
                    </span>
                 </div>
                 <div>
                     <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $message->subject }}</h3>
                     <p class="text-sm text-gray-500">From: {{ $message->sender->name }} • {{ $message->created_at->format('M d, Y h:i A') }}</p>
                 </div>
            </div>
            <div class="flex space-x-3">
                 <a href="{{ route('messages.reply', $message) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reply
                </a>
                <a href="{{ route('messages.index') }}" class="text-sm text-blue-600 hover:text-blue-500 self-center">
                    &larr; Back to Inbox
                </a>
            </div>
        </div>
        <div class="prose max-w-none text-gray-800 whitespace-pre-line">
            {{ $message->body }}
        </div>
    </div>
</div>
@endsection
