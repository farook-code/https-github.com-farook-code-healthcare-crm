<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $currentSubscription = Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->where('ends_at', '>', Carbon::now())
            ->latest()
            ->with('plan')
            ->first();
            
        return view('subscriptions.index', compact('plans', 'currentSubscription'));
    }

    public function checkout(Plan $plan)
    {
        return view('subscriptions.checkout', compact('plan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        // Cancel existing active subscriptions
        Subscription::where('user_id', Auth::id())
            ->where('status', 'active')
            ->update(['status' => 'cancelled']); // Keep original ends_at or set to now? Usually upgrade happens immediately.

        // Create new
        Subscription::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addDays($plan->duration_in_days),
        ]);

        return redirect()->route('subscriptions.index')->with('success', 'You have successfully subscribed to the ' . $plan->name . ' plan!');
    }
}
