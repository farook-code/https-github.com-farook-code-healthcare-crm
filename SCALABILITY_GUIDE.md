# 🚀 HealthFlow Scaling Guide (1 Lakh+ Users)

## 📌 Introduction
This application is designed with a monolithic architecture using Laravel. While the **codebase** is optimized for performance (caching, indexing, eager loading), handling **100,000 users** requires solid **Infrastructure** choices.

Here is your checklist for High-Scale Deployment:

## 1. 🗄️ Database (MySQL/MariaDB)
With 1 Lakh users, you cannot run the Database on the same server as the App.
- **Requirement**: Use a Managed Database (e.g., AWS RDS, DigitalOcean Managed Database).
- **Optimization**: The indexes created in `2026_01_21_120000_add_indexes_to_tables.php` are CRITICAL. Ensure migrations are run.
- **Read Replicas**: If reporting is slow, forward ALL `select` queries to a Read Replica.

## 2. ⚡ Caching & Queues (Redis)
Do **NOT** use `file` or `database` drivers for Cache/Queue in high production.
- **Install Redis**: `sudo apt install redis-server`
- **Configuration**: Update `.env`:
  ```
  CACHE_DRIVER=redis
  QUEUE_CONNECTION=redis
  SESSION_DRIVER=redis
  ```
- **Why?**: Database queues lock rows and slow down the app. Redis handles 100k ops/second instantly.

## 3. 🔄 Background Workers (Supervisor)
You must run Queue Workers to process emails (Stock Alerts), Reports, and heavy tasks in the background.
- Install **Supervisor** to keep `php artisan queue:work` running forever.
- If you have 100k users, run **10-20 worker processes**.

## 4. 🌐 Web Server (Nginx + PHP-FPM)
- **PHP-FPM Tuning**:
  - `pm.max_children`: Increase this based on RAM. (e.g., 50-100 for 4GB RAM).
  - Use **OPCache**: Ensure `opcache.enable=1` and `opcache.validate_timestamps=0` in production.

## 5. 🧹 Maintenance
- **Pruning**: We added `php artisan audit:prune`. Run this nightly via Cron to prevent the Audit Log table from reaching millions of rows.
  ```
  0 0 * * * php /path/to/artisan audit:prune
  ```

## 6. 🛑 Rate Limiting
To prevent abuse from 100k users:
- The `ThrottleRequests` middleware is enabled by default.
- Consider using **Cloudflare** for DDoS protection.

---
**Verdict**: The Code is ready. The Infrastructure is up to you!
