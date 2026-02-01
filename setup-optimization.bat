@echo off
echo ====================================
echo  CareSync - Phase 2 Optimization
echo  Quick Setup Script
echo ====================================
echo.

echo [1/7] Checking Redis...
redis-cli ping >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Redis is not running!
    echo Please start Redis first: redis-server.exe
    pause
    exit /b 1
)
echo [OK] Redis is running

echo.
echo [2/7] Clearing caches...
call php artisan cache:clear
call php artisan config:clear
call php artisan route:clear
call php artisan view:clear

echo.
echo [3/7] Running database migrations...
call php artisan migrate --force

echo.
echo [4/7] Caching configuration...
call php artisan config:cache
call php artisan route:cache

echo.
echo [5/7] Testing cache connection...
call php artisan tinker --execute="Cache::put('test', 'work', 60); echo Cache::get('test');"

echo.
echo [6/7] Optimizing composer autoload...
call composer dump-autoload -o

echo.
echo [7/7] Starting queue worker...
echo Note: Queue worker will run in this window. Open a new terminal for other commands.
echo.
call php artisan queue:work redis --sleep=3 --tries=3

pause
