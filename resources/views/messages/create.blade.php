@extends('layouts.dashboard')

@section('header', 'Compose Message')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
        <form action="{{ route('messages.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="receiver_id" class="block text-sm font-medium text-gray-700">To</label>
                <select id="receiver_id" name="receiver_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (isset($replyTo) && $replyTo == $user->id) ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->role->name ?? 'Staff' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" id="subject" value="{{ $subject ?? '' }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea id="body" name="body" rows="6" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('messages.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
