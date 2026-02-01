<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatMessage;

class ChatSystemMessage
{
    /**
     * Send a system message to a user.
     *
     * @param int $senderId The ID of the user sending the message (e.g., Doctor/Admin)
     * @param int $receiverId The ID of the user receiving the message (e.g., Patient)
     * @param string $message The content of the message
     * @param string|null $attachmentPath Optional attachment path
     * @param string|null $attachmentType Optional attachment type
     * @return void
     */
    public static function send($senderId, $receiverId, $message, $attachmentPath = null, $attachmentType = null)
    {
        // Normalize IDs to ensure consistent storage (user_one < user_two)
        // This prevents duplicate chats like (1, 2) and (2, 1) if the App uses that convention,
        // or just safely finds the existing one if we stick to a convention.
        // However, checking existing data: The current error implies (2, 25) already exists.
        
        // Let's try to find it first loosely as before to be safe about existing data, 
        // then try-catch the creation.
        
        $chat = Chat::where(function ($q) use ($senderId, $receiverId) {
                $q->where('user_one_id', $senderId)
                  ->where('user_two_id', $receiverId);
            })
            ->orWhere(function ($q) use ($senderId, $receiverId) {
                $q->where('user_one_id', $receiverId)
                  ->where('user_two_id', $senderId);
            })
            ->first();

        if (!$chat) {
            // If strictly ensuring unique pair constraint failed, it might be due to a race condition.
            // We use firstOrCreate with the specific direction we intended.
            try {
                $chat = Chat::create([
                    'user_one_id' => $senderId,
                    'user_two_id' => $receiverId
                ]);
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                // Race condition: it was created just now by someone else, or existed and our first check failed (unlikely)
                $chat = Chat::where('user_one_id', $senderId)->where('user_two_id', $receiverId)->first();
                
                // Fallback: try reverse direction if not found
                if (!$chat) {
                    $chat = Chat::where('user_one_id', $receiverId)->where('user_two_id', $senderId)->firstOrFail();
                }
            }
        }

        $type = 'text';
        if ($attachmentType) {
            $type = in_array($attachmentType, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : 'file';
        }

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $senderId,
            'message' => $message,
            'type' => $type,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'is_read' => false
        ]);
    }
}
