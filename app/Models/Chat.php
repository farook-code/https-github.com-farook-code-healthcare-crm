<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['user_one_id', 'user_two_id'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(ChatMessage::class)->where('sender_id', '!=', auth()->id())->where('is_read', false);
    }
}
