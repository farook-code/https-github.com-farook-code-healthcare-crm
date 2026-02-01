<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PerformanceTest extends TestCase
{
    /**
     * Test database query performance
     */
    public function test_appointment_list_performance()
    {
        // Create test data
        $patient = Patient::factory()->create();
        $doctor = User::factory()->create(['role_id' => 2]); // Doctor role
        Appointment::factory()->count(100)->create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        // Measure query count
        DB::enableQueryLog();
        
        $startTime = microtime(true);
        
        // Load appointments with relationships (optimized)
        $appointments = Appointment::with(['patient', 'doctor'])
            ->paginate(50);
        
        $endTime = microtime(true);
        $queries = DB::getQueryLog();
        
        $executionTime = ($endTime - $startTime) * 1000; // ms
        $queryCount = count($queries);

        // Assertions
        $this->assertLessThan(5, $queryCount, "Should use less than 5 queries (eager loading)");
        $this->assertLessThan(100, $executionTime, "Should execute in less than 100ms");
        
        echo "\n\nPerformance Metrics:";
        echo "\nExecution Time: " . round($executionTime, 2) . "ms";
        echo "\nQuery Count: " . $queryCount;
        echo "\n";
    }

    /**
     * Test cache performance
     */
    public function test_cache_performance()
    {
        $key = 'test:cache:performance';
        
        // First call (not cached)
        Cache::forget($key);
        $startTime = microtime(true);
        $data = Cache::remember($key, 60, function () {
            sleep(1); // Simulate expensive operation
            return ['value' => 'test'];
        });
        $firstCallTime = (microtime(true) - $startTime) * 1000;
        
        // Second call (cached)
        $startTime = microtime(true);
        $data = Cache::get($key);
        $cachedCallTime = (microtime(true) - $startTime) * 1000;
        
        // Assertions
        $this->assertGreaterThan(1000, $firstCallTime, "First call should take > 1 second");
        $this->assertLessThan(10, $cachedCallTime, "Cached call should be < 10ms");
        
        echo "\n\nCache Performance:";
        echo "\nFirst Call (no cache): " . round($firstCallTime, 2) . "ms";
        echo "\nCached Call: " . round($cachedCallTime, 2) . "ms";
        echo "\nSpeedup: " . round($firstCallTime / $cachedCallTime, 0) . "x faster";
        echo "\n";
    }

    /**
     * Test pagination performance
     */
    public function test_pagination_memory_usage()
    {
        Patient::factory()->count(1000)->create();
        
        // Without pagination
        $startMemory = memory_get_usage();
        $allPatients = Patient::all(); // Loads all 1000
        $memoryWithoutPagination = (memory_get_usage() - $startMemory) / 1024; // KB
        
        // With pagination
        $startMemory = memory_get_usage();
        $paginatedPatients = Patient::paginate(50); // Loads only 50
        $memoryWithPagination = (memory_get_usage() - $startMemory) / 1024; // KB
        
        echo "\n\nPagination Memory Test:";
        echo "\nWithout Pagination (1000 records): " . round($memoryWithoutPagination, 2) . " KB";
        echo "\nWith Pagination (50 records): " . round($memoryWithPagination, 2) . " KB";
        echo "\nMemory Saved: " . round(100 - ($memoryWithPagination / $memoryWithoutPagination * 100), 2) . "%";
        echo "\n";
        
        $this->assertLessThan($memoryWithoutPagination, $memoryWithPagination);
    }

    /**
     * Test index effectiveness
     */
    public function test_indexed_query_performance()
    {
        Patient::factory()->count(10000)->create();
        
        // Query on indexed column
        DB::enableQueryLog();
        $startTime = microtime(true);
        $patients = Patient::where('id', 5000)->first();
        $indexedTime = (microtime(true) - $startTime) * 1000;
        $queries = DB::getQueryLog();
        
        echo "\n\nIndex Performance Test:";
        echo "\nIndexed Query Time: " . round($indexedTime, 2) . "ms";
        echo "\nQuery: " . $queries[0]['query'];
        echo "\n";
        
        $this->assertLessThan(50, $indexedTime, "Indexed query should be < 50ms");
    }

    /**
     * Test N+1 problem detection
     */
    public function test_n_plus_one_detection()
    {
        $patients = Patient::factory()->count(10)->create();
        foreach ($patients as $patient) {
            Appointment::factory()->count(5)->create(['patient_id' => $patient->id]);
        }

        // BAD: N+1 Problem
        DB::enableQueryLog();
        $appointments = Appointment::all();
        foreach ($appointments as $appointment) {
            $patientName = $appointment->patient->name; // N+1 here
        }
        $badQueryCount = count(DB::getQueryLog());
        
        // GOOD: Eager Loading
        DB::flushQueryLog();
        $appointments = Appointment::with('patient')->get();
        foreach ($appointments as $appointment) {
            $patientName = $appointment->patient->name;
        }
        $goodQueryCount = count(DB::getQueryLog());
        
        echo "\n\nN+1 Problem Detection:";
        echo "\nWithout Eager Loading: " . $badQueryCount . " queries";
        echo "\nWith Eager Loading: " . $goodQueryCount . " queries";
        echo "\nQueries Saved: " . ($badQueryCount - $goodQueryCount);
        echo "\n";
        
        $this->assertLessThan($badQueryCount, $goodQueryCount);
    }
}
