<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        // Assign "Patient" role by default for public registration
        'role_id' => \App\Models\Role::where('slug', 'patient')->first()->id ?? 4, 
    ]);

    // Create Patient Profile
    \App\Models\Patient::create([
        'user_id' => $user->id,
        'patient_code' => 'PT-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
        'name' => $user->name,
        'email' => $user->email,
        // Other fields nullable
    ]);

    event(new Registered($user));

    Auth::login($user);

    // Initial Plan Assignment
    if ($request->has('plan')) {
        $plan = \App\Models\Plan::where('slug', $request->plan)->first();
        if ($plan) {
            \App\Models\Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration_in_days),
            ]);
        }
    } else {
        // Fallback to Basic free plan if exists
        $basicPlan = \App\Models\Plan::where('slug', 'basic')->first();
        if ($basicPlan) {
             \App\Models\Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $basicPlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addDays($basicPlan->duration_in_days),
            ]);
        }
    }

    return redirect('/patient/dashboard');
}
}
