<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/magic-login/{id}', function ($id) {
    if (!app()->environment('local')) {
        abort(403);
    }
    
    $user = User::find($id);
    
    if (!$user) {
        return "User ID {$id} not found.";
    }
    
    Auth::login($user);
    
    return redirect('/dashboard');
});
