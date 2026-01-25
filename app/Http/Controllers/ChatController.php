<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::where('user_one_id', auth()->id())
            ->orWhere('user_two_id', auth()->id())
            ->with(['messages', 'userOne', 'userTwo', 'unreadMessages'])
            ->get();

        return view('chat.index', compact('chats'));
    }

    public function open($userId)
    {
        $chat = Chat::where(function ($q) use ($userId) {
                $q->where('user_one_id', auth()->id())
                  ->where('user_two_id', $userId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('user_one_id', $userId)
                  ->where('user_two_id', auth()->id());
            })
            ->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_one_id' => auth()->id(),
                'user_two_id' => $userId
            ]);
        }

        $messages = $chat->messages()->orderBy('created_at')->get();

        // ✅ Mark messages as read
        $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $chats = Chat::where('user_one_id', auth()->id())
            ->orWhere('user_two_id', auth()->id())
            ->with(['userOne', 'userTwo'])
            ->get();

        return view('chat.show', compact('chat', 'messages', 'chats'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:10240|mimes:jpeg,png,jpg,gif,pdf,doc,docx'
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment required'], 422);
        }

        $chat = Chat::findOrFail($request->chat_id);

        // Authorization check
        if (
            $chat->user_one_id !== auth()->id() &&
            $chat->user_two_id !== auth()->id()
        ) {
            abort(403);
        }

        $attachmentPath = null;
        $attachmentType = null;
        $type = 'text';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat_attachments', 'public');
            $attachmentPath = '/storage/' . $path;
            $attachmentType = $file->getClientOriginalExtension();
            $type = in_array($attachmentType, ['jpg','jpeg','png','gif']) ? 'image' : 'file';
        }

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'type' => $type,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType
        ]);

        return response()->json(['status' => 'sent']);
    }

    public function fetch(Chat $chat)
    {
        // ✅ Authorization check
        if (
            $chat->user_one_id !== auth()->id() &&
            $chat->user_two_id !== auth()->id()
        ) {
            abort(403);
        }

        // ✅ Mark messages as read
        $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.partials.messages', [
            'messages' => $chat->messages()->orderBy('created_at')->get()
        ]);
    }

    public function unreadCount()
    {
        $count = ChatMessage::where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }

    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = \App\Models\User::where('name', 'like', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($users);
    }
}
