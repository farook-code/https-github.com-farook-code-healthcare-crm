<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoring
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        // Enable query logging
        DB::enableQueryLog();
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = round(($endTime - $startTime) * 1000, 2); // ms
        $memoryUsed = round(($endMemory - $startMemory) / 1024 / 1024, 2); // MB
        $queries = DB::getQueryLog();
        $queryCount = count($queries);
        
        // Calculate total query time
        $totalQueryTime = 0;
        foreach ($queries as $query) {
            $totalQueryTime += $query['time'];
        }
        
        $performanceData = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'execution_time_ms' => $executionTime,
            'memory_used_mb' => $memoryUsed,
            'query_count' => $queryCount,
            'total_query_time_ms' => round($totalQueryTime, 2),
            'user_id' => auth()->id(),
        ];
        
        // Log slow requests (over 1 second)
        if ($executionTime > 1000) {
            Log::warning('Slow Request Detected', array_merge($performanceData, [
                'queries' => $queries,
            ]));
        }
        
        // Log requests with too many queries (N+1 problem indicator)
        if ($queryCount > 50) {
            Log::warning('High Query Count Detected', array_merge($performanceData, [
                'first_10_queries' => array_slice($queries, 0, 10),
            ]));
        }
        
        // Add performance headers in development
        if (config('app.debug')) {
            $response->headers->set('X-Execution-Time', $executionTime . 'ms');
            $response->headers->set('X-Memory-Used', $memoryUsed . 'MB');
            $response->headers->set('X-Query-Count', $queryCount);
            $response->headers->set('X-Query-Time', round($totalQueryTime, 2) . 'ms');
        }
        
        return $response;
    }
}
