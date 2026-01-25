<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'is_read',
        'type',
        'attachment_path',
        'attachment_type'
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}

