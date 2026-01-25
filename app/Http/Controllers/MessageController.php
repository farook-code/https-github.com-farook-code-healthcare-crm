<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $messages = Message::where('receiver_id', $userId)
            ->with('sender')
            ->latest()
            ->paginate(10);
            
        return view('messages.index', compact('messages'));
    }

    public function create()
    {
        $users = User::whereHas('role', function($q){
            $q->whereIn('slug', ['admin', 'doctor', 'nurse', 'reception']);
        })->where('id', '!=', auth()->id())->get();

        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('messages.index')->with('success', 'Message sent successfully.');
    }
    
    public function show(Message $message)
    {
        if(auth()->id() !== $message->receiver_id && auth()->id() !== $message->sender_id) {
            abort(403);
        }

        if(auth()->id() === $message->receiver_id && !$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('messages.show', compact('message'));
    }
    public function reply(Message $message)
    {
         if(auth()->id() !== $message->receiver_id) {
            abort(403);
        }

        $users = User::whereHas('role', function($q){
            $q->whereIn('slug', ['admin', 'doctor', 'nurse', 'reception']);
        })->where('id', '!=', auth()->id())->get();

        $replyTo = $message->sender_id;
        $subject = 'RE: ' . $message->subject;
        
        return view('messages.create', compact('users', 'replyTo', 'subject'));
    }
}
