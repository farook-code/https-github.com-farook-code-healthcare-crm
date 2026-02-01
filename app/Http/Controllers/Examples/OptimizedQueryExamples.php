<?php

namespace App\Http\Controllers\Examples;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * OPTIMIZED QUERY EXAMPLES
 * 
 * This file contains before/after examples of optimized database queries
 * Copy these patterns to your controllers for better performance
 */
class OptimizedQueryExamples
{
    /**
     * ❌ BAD: N+1 Query Problem
     * This will execute 1 + N queries (1 for appointments, N for each patient/doctor)
     */
    public function badExample()
    {
        $appointments = Appointment::all(); // 1 query
        
        foreach ($appointments as $appointment) {
            echo $appointment->patient->name;  // N queries (one per appointment)
            echo $appointment->doctor->name;   // N more queries
        }
        
        // Total: 1 + 2N queries 😱
    }

    /**
     * ✅ GOOD: Eager Loading
     * This executes only 3 queries total
     */
    public function goodExample()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->get(); // 3 queries total
        
        foreach ($appointments as $appointment) {
            echo $appointment->patient->name;
            echo $appointment->doctor->name;
        }
        
        // Total: 3 queries 🎉
    }

    /**
     * ❌ BAD: Loading all records
     */
    public function badPagination()
    {
        $patients = Patient::all(); // Loads ALL patients into memory
        return view('patients.index', compact('patients'));
    }

    /**
     * ✅ GOOD: Pagination
     */
    public function goodPagination()
    {
        $patients = Patient::paginate(50); // Only loads 50 at a time
        return view('patients.index', compact('patients'));
    }

    /**
     * ❌ BAD: Multiple separate queries
     */
    public function badDashboard()
    {
        $totalPatients = Patient::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $pendingInvoices = DB::table('invoices')->where('status', 'unpaid')->count();
        
        // 3 separate queries
    }

    /**
     * ✅ GOOD: Combined query with caching
     */
    public function goodDashboard()
    {
        $stats = Cache::remember('dashboard:stats', 300, function () {
            return [
                'total_patients' => Patient::count(),
                'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
                'pending_invoices' => DB::table('invoices')->where('status', 'unpaid')->count(),
            ];
        });
        
        // Cached for 5 minutes, only 3 queries every 5 minutes
    }

    /**
     * ❌ BAD: Selecting all columns
     */
    public function badSelect()
    {
        $doctors = User::where('role_id', function($query) {
            $query->select('id')->from('roles')->where('slug', 'doctor');
        })->get();
        
        // Loads ALL columns including unnecessary data
    }

    /**
     * ✅ GOOD: Select only needed columns
     */
    public function goodSelect()
    {
        $doctors = User::select('id', 'name', 'email')
            ->where('role_id', function($query) {
                $query->select('id')->from('roles')->where('slug', 'doctor');
            })
            ->get();
        
        // Only loads needed columns
    }

    /**
     * ❌ BAD: whereHas with all data
     */
    public function badWhereHas()
    {
        $patientsWithAppointments = Patient::whereHas('appointments')->get();
        
        // Loads all patients with appointments
    }

    /**
     * ✅ GOOD: whereHas with specific conditions
     */
    public function goodWhereHas()
    {
        $patientsWithTodayAppointments = Patient::whereHas('appointments', function($query) {
            $query->whereDate('appointment_date', today());
        })->with('appointments:id,patient_id,appointment_date,status')
          ->get();
        
        // Only patients with appointments today, with limited appointment data
    }

    /**
     * ❌ BAD: Using loops for aggregation
     */
    public function badAggregation()
    {
        $patients = Patient::all();
        $totalRevenue = 0;
        
        foreach ($patients as $patient) {
            $totalRevenue += $patient->invoices->sum('total_amount'); // N queries
        }
    }

    /**
     * ✅ GOOD: Database aggregation
     */
    public function goodAggregation()
    {
        $totalRevenue = DB::table('invoices')->sum('total_amount'); // 1 query
    }

    /**
     * ❌ BAD: Multiple updates in loop
     */
    public function badBulkUpdate()
    {
        $appointments = Appointment::where('status', 'scheduled')
            ->whereDate('appointment_date', '<', today())
            ->get();
        
        foreach ($appointments as $appointment) {
            $appointment->update(['status' => 'missed']); // N queries
        }
    }

    /**
     * ✅ GOOD: Bulk update
     */
    public function goodBulkUpdate()
    {
        Appointment::where('status', 'scheduled')
            ->whereDate('appointment_date', '<', today())
            ->update(['status' => 'missed']); // 1 query
    }

    /**
     * ❌ BAD: No indexes on search columns
     */
    public function badSearch($searchTerm)
    {
        $patients = Patient::where('first_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
            ->get();
        
        // Full table scan - very slow
    }

    /**
     * ✅ GOOD: Indexed search with caching
     */
    public function goodSearch($searchTerm)
    {
        // Make sure first_name and last_name are indexed
        $cacheKey = "patient:search:" . md5($searchTerm);
        
        $patients = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return Patient::select('id', 'first_name', 'last_name', 'medical_record_number')
                ->where(function($query) use ($searchTerm) {
                    $query->where('first_name', 'LIKE', "{$searchTerm}%") // Prefix search (uses index)
                          ->orWhere('last_name', 'LIKE', "{$searchTerm}%")
                          ->orWhere('medical_record_number', 'LIKE', "{$searchTerm}%");
                })
                ->limit(50)
                ->get();
        });
        
        // Uses indexes + caching
    }

    /**
     * ✅ BEST: Using chunking for large datasets
     */
    public function processingLargeDataset()
    {
        // Instead of loading all records at once
        Patient::chunk(1000, function ($patients) {
            foreach ($patients as $patient) {
                // Process patient
                // This processes 1000 at a time, freeing memory after each chunk
            }
        });
    }

    /**
     * ✅ BEST: Lazy loading for memory efficiency
     */
    public function lazyLoading()
    {
        // For very large datasets, use lazy() instead of chunk()
        foreach (Patient::lazy() as $patient) {
            // Process one at a time
            // Very memory efficient
        }
    }

    /**
     * ✅ Using exists() instead of count()
     */
    public function checkExistence()
    {
        // ❌ BAD
        if (Appointment::where('patient_id', $patientId)->count() > 0) {
            // ...
        }
        
        // ✅ GOOD
        if (Appointment::where('patient_id', $patientId)->exists()) {
            // Much faster, stops after finding first match
        }
    }

    /**
     * ✅ Select specific columns with relationships
     */
    public function selectWithRelations()
    {
        $appointments = Appointment::select('id', 'patient_id', 'doctor_id', 'appointment_date', 'status')
            ->with([
                'patient:id,first_name,last_name',
                'doctor:id,name'
            ])
            ->paginate(50);
    }

    /**
     * ✅ Using database transactions for consistency
     */
    public function useTransactions()
    {
        DB::transaction(function () {
            $appointment = Appointment::create([...]);
            $invoice = Invoice::create([...]);
            $notification = Notification::create([...]);
        });
        
        // All succeed or all fail together
    }
}
