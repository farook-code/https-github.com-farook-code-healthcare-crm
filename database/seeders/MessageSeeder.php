<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Chat;
use App\Models\ChatMessage;

class MessageSeeder extends Seeder
{
    public function run()
    {
        // 1. Get key users
        $admin = User::where('email', 'admin@healthcare.com')->first();
        $doctor = User::where('email', 'doctor@healthcare.com')->first();
        $patient = User::where('email', 'patient@healthcare.com')->first();
        $nurse = User::whereHas('role', function($q) { $q->where('slug', 'nurse'); })->first();

        if (!$admin || !$doctor || !$patient) return;

        // 2. Chat: Doctor <-> Patient
        $chat1 = Chat::firstOrCreate([
            'user_one_id' => min($doctor->id, $patient->id),
            'user_two_id' => max($doctor->id, $patient->id),
        ]);

        ChatMessage::create([
            'chat_id' => $chat1->id,
            'sender_id' => $patient->id,
            'message' => 'Dr. Smith, I have a question about my medication.',
            'is_read' => true,
            'created_at' => now()->subDay()
        ]);

        ChatMessage::create([
            'chat_id' => $chat1->id,
            'sender_id' => $doctor->id,
            'message' => 'Sure, what seems to be the trouble?',
            'is_read' => true,
            'created_at' => now()->subDay()->addMinutes(10)
        ]);

        ChatMessage::create([
            'chat_id' => $chat1->id,
            'sender_id' => $patient->id,
            'message' => 'I feel a bit dizzy after taking it.',
            'is_read' => false, // Unread for doctor
            'created_at' => now()->subMinutes(30)
        ]);

        // 3. Chat: Admin <-> Doctor
        $chat2 = Chat::firstOrCreate([
            'user_one_id' => min($admin->id, $doctor->id),
            'user_two_id' => max($admin->id, $doctor->id),
        ]);

        ChatMessage::create([
            'chat_id' => $chat2->id,
            'sender_id' => $admin->id,
            'message' => 'Don\'t forget your schedule update for next month.',
            'is_read' => true,
            'created_at' => now()->subDays(2)
        ]);
        
        ChatMessage::create([
            'chat_id' => $chat2->id,
            'sender_id' => $doctor->id,
            'message' => 'Will do, thanks!',
            'is_read' => false, // Unread for admin
            'created_at' => now()->subDays(2)->addHours(1)
        ]);
    }
}
