<?php

/**
 * PERFORMANCE OPTIMIZATION CHECKLIST FOR HIGH TRAFFIC
 * 
 * Apply these changes to handle 1 billion+ traffic
 */

// ============================================
// 1. DATABASE QUERY OPTIMIZATION
// ============================================

// ❌ BAD: N+1 Query Problem
$appointments = Appointment::all();
foreach ($appointments as $appointment) {
    echo $appointment->patient->name; // Hits DB for each patient
}

// ✅ GOOD: Eager Loading
$appointments = Appointment::with('patient', 'doctor')->get();


// ❌ BAD: Loading all records
$patients = Patient::all();

// ✅ GOOD: Pagination + Chunk
$patients = Patient::paginate(50);
Patient::chunk(1000, function ($patients) {
    // Process in batches
});


// ============================================
// 2. CACHING STRATEGY
// ============================================

// Cache frequently accessed data
Cache::remember('departments_list', 3600, function () {
    return Department::where('status', 'active')->get();
});

// Cache user permissions
Cache::remember("user_permissions_{$userId}", 3600, function () use ($userId) {
    return User::find($userId)->permissions;
});


// ============================================
// 3. QUEUE HEAVY TASKS
// ============================================

// ❌ BAD: Sync email (blocks request)
Mail::to($patient)->send(new AppointmentConfirmation($appointment));

// ✅ GOOD: Queue email (async)
Mail::to($patient)->queue(new AppointmentConfirmation($appointment));


// ============================================
// 4. INDEX ALL FOREIGN KEYS
// ============================================

Schema::table('appointments', function (Blueprint $table) {
    $table->index('patient_id');
    $table->index('doctor_id');
    $table->index('status');
    $table->index('appointment_date');
    $table->index(['status', 'appointment_date']); // Composite index
});


// ============================================
// 5. API RATE LIMITING
// ============================================

// In RouteServiceProvider or routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // 60 requests per minute per IP
});


// ============================================
// 6. DATABASE READ/Write Splitting
// ============================================

// config/database.php
'mysql' => [
    'write' => [
        'host' => env('DB_HOST', '127.0.0.1'),
    ],
    'read' => [
        [
            'host' => env('DB_READ_HOST_1', '127.0.0.1'),
        ],
        [
            'host' => env('DB_READ_HOST_2', '127.0.0.1'),
        ],
    ],
],


// ============================================
// 7. OPTIMIZE IMAGES & ASSETS
// ============================================

// Use CDN for static assets
config(['app.asset_url' => 'https://cdn.yourdomain.com']);

// Image optimization
use Intervention\Image\Facades\Image;
Image::make($file)->fit(800)->save($path);


// ============================================
// 8. IMPLEMENT FULL-TEXT SEARCH
// ============================================

// Use Laravel Scout with Elasticsearch/Algolia
Patient::search('John Doe')->get();

// Add indexes to searchable columns
$table->fullText(['first_name', 'last_name', 'email']);


// ============================================
// 9. MONITORING & LOGGING
// ============================================

// Use APM tools
// - New Relic
// - DataDog
// - AWS CloudWatch

// Log slow queries
DB::listen(function ($query) {
    if ($query->time > 1000) { // 1 second
        Log::warning("Slow Query: {$query->sql}", ['time' => $query->time]);
    }
});


// ============================================
// 10. HORIZONTAL SCALING
// ============================================

// Use stateless sessions (Redis)
// Deploy on multiple servers behind load balancer
// Use database connection pooling
// Implement circuit breakers for external APIs

