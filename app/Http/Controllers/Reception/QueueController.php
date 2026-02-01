<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    // Public Facing TV Display
    public function display()
    {
        // Fetch appointments that are 'in_progress' or 'scheduled' for today
        // Ordered by time
        $serving = Appointment::whereDate('appointment_date', today())
            ->where('status', 'in_progress')
            ->with(['doctor', 'patient'])
            ->get();

        $waiting = Appointment::whereDate('appointment_date', today())
            ->where('status', 'scheduled')
            ->orderBy('appointment_time', 'asc')
            ->take(5)
            ->with(['patient'])
            ->get();

        return view('reception.queue.display', compact('serving', 'waiting'));
    }
}
