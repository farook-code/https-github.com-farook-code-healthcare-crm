<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemAlert;
use App\Services\AlertService;
use Illuminate\Http\Request;

class SystemAlertController extends Controller
{
    public function index()
    {
        $alerts = SystemAlert::latest()->paginate(20);
        return view('admin.alerts.index', compact('alerts'));
    }

    public function markAsResolved(SystemAlert $alert)
    {
        AlertService::resolve($alert->id, auth()->id());
        return back()->with('success', 'Alert marked as resolved.');
    }
    
    public function fetchActive()
    {
        $alerts = SystemAlert::active()->critical()->latest()->take(5)->get();
        $count = SystemAlert::active()->count();
        
        return response()->json([
            'count' => $count,
            'alerts' => $alerts
        ]);
    }
}
