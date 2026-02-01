<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MagicLinkController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate Signed URL
        $url = URL::temporarySignedRoute(
            'login.magic.verify',
            now()->addMinutes(30),
            ['user' => $user->id]
        );

        // In a real app, send email. For demo/local, just Log it or Flash it.
        // We'll log it for debugging and flash a success message.
        Log::info("Magic Link for {$user->email}: {$url}");

        // If local, maybe showing it in the flash message is helpful for the user?
        if (app()->environment('local')) {
            session()->flash('magic_link_url', $url); // Only for demo convenience
        }

        return back()->with('status', 'We have emailed you a magic login link!');
    }

    public function verify(Request $request, User $user)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired magic link.');
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
