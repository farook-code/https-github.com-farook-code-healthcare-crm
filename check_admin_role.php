<?php
use App\Models\User;

$user = User::where('email', 'admin@healthcare.com')->with('role')->first();

if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User ID: " . $user->id . "\n";
echo "Role ID: " . $user->role_id . "\n";
if ($user->role) {
    echo "Role Slug: " . $user->role->slug . "\n";
} else {
    echo "Role Relationship is NULL\n";
}
