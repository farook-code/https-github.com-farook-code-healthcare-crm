# PHASE 2 OPTIMIZATION DEPLOYMENT GUIDE
# Medium Scale (10K-100K Users)

## Prerequisites

### 1. Install Redis on Windows (Laragon)
```powershell
# Download Redis for Windows from: https://github.com/microsoftarchive/redis/releases
# Or use Memurai (Redis-compatible): https://www.memurai.com/

# For Laragon, you can add Redis as a service:
# 1. Download Redis
# 2. Extract to C:\laragon\bin\redis\
# 3. Start Redis: redis-server.exe
```

### 2. Create MySQL Database
```sql
CREATE DATABASE healthcare_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'healthcare_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON healthcare_crm.* TO 'healthcare_user'@'localhost';
FLUSH PRIVILEGES;
```

## Step-by-Step Deployment

### Step 1: Update Environment
```bash
# Copy production environment file
cp .env.production .env

# Update database credentials in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=healthcare_crm
DB_USERNAME=root
DB_PASSWORD=

# Enable Redis
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Step 2: Install Dependencies
```bash
# Install PHP Redis extension
# For Windows: Download php_redis.dll from PECL
# Add to php.ini: extension=redis

# Verify Redis is running
redis-cli ping
# Should return: PONG
```

### Step 3: Migrate Database
```bash
# Clear existing database
php artisan migrate:fresh

# Run all migrations include new indexes
php artisan migrate

# Seed database
php artisan db:seed
```

### Step 4: Clear & Warm Cache
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Start Queue Workers
```bash
# Start queue worker (keep this running)
php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600

# For production, use Supervisor (Linux) or NSSM (Windows)
# Windows: Download NSSM from https://nssm.cc/download

# Install as Windows service
nssm install LaravelQueue "C:\path\to\php.exe" "C:\laragon\www\crm-dashboard\artisan queue:work redis --sleep=3 --tries=3"
nssm start LaravelQueue
```

### Step 6: Schedule Cron Jobs (Optional)
```bash
# Add to Windows Task Scheduler or use Laravel Scheduler

# Create batch file: scheduler.bat
cd C:\laragon\www\crm-dashboard
php artisan schedule:run

# Add to Task Scheduler to run every minute
```

## Testing Performance

### 1. Test Redis Connection
```bash
php artisan tinker

# Test cache
Cache::put('test', 'value', 60);
Cache::get('test'); // Should return 'value'

# Test queue
dispatch(function () {
    logger('Queue is working!');
});
```

### 2. Test Database Performance
```bash
# Check slow queries
php artisan tinker

DB::listen(function ($query) {
    if ($query->time > 100) {
        dump("Slow query: {$query->sql} ({$query->time}ms)");
    }
});

# Run a query
App\Models\Appointment::with('patient', 'doctor')->get();
```

### 3. Load Testing
```bash
# Install Apache Bench (comes with Apache)
# Or use: https://github.com/rakyll/hey

# Test 1000 requests with 100 concurrent users
ab -n 1000 -c 100 http://crm-dashboard.test/

# Expected result:
# - Requests per second: 100-500
# - Time per request: 10-100ms
```

## Performance Monitoring

### 1. Enable Query Logging
```php
// In AppServiceProvider.php boot() method
if (app()->environment('production')) {
    DB::listen(function ($query) {
        if ($query->time > 1000) {
            Log::warning('Slow Query', [
                'sql' => $query->sql,
                'time' => $query->time,
                'bindings' => $query->bindings,
            ]);
        }
    });
}
```

### 2. Monitor Redis
```bash
# Redis CLI
redis-cli

# Monitor commands
MONITOR

# Check memory usage
INFO memory

# Check connected clients
CLIENT LIST
```

### 3. Monitor MySQL
```sql
-- Show slow queries
SHOW PROCESSLIST;

-- Show table sizes
SELECT 
    table_name AS `Table`,
    round(((data_length + index_length) / 1024 / 1024), 2) `Size (MB)`
FROM information_schema.TABLES
WHERE table_schema = 'healthcare_crm'
ORDER BY (data_length + index_length) DESC;

-- Show index usage
SHOW INDEX FROM appointments;
```

## Expected Performance Improvements

| Metric | Before | After | Improvement |
|--------|--------|-------|-------------|
| Page Load Time | 500-1000ms | 100-200ms | 5x faster |
| Dashboard Load | 2-3s | 300-500ms | 6x faster |
| Concurrent Users | 100 | 10,000 | 100x |
| Requests/second | 10-20 | 100-500 | 25x |
| Database Queries | 100+ per page | 10-20 per page | 80% reduction |

## Troubleshooting

### Redis not working
```bash
# Check if Redis is running
redis-cli ping

# Check PHP extension
php -m | grep redis

# Check logs
tail -f storage/logs/laravel.log
```

### Queue not processing
```bash
# Check queue worker is running
php artisan queue:work redis --once

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Database slow
```bash
# Analyze tables
php artisan tinker
DB::statement('ANALYZE TABLE appointments');

# Rebuild indexes
php artisan migrate:refresh --path=database/migrations/2026_02_01_120000_add_comprehensive_indexes.php
```

## Next Steps (Phase 3)

1. ✅ Implement load balancer
2. ✅ Add database read replicas
3. ✅ Use CDN for assets
4. ✅ Implement Elasticsearch for search
5. ✅ Add APM monitoring (New Relic/DataDog)

## Maintenance

### Daily
- Monitor queue workers
- Check error logs
- Monitor disk space

### Weekly  
- Review slow query logs
- Optimize database tables
- Clear old cache entries

### Monthly
- Database backup
- Performance audit
- Update dependencies
