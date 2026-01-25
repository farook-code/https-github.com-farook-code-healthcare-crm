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
        // Find or create chat
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
            $chat = Chat::create([
                'user_one_id' => $senderId,
                'user_two_id' => $receiverId
            ]);
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
