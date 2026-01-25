<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AuditLog;
use Carbon\Carbon;

class PruneAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:prune {--days=90 : Number of days to retain logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old audit logs to keep database size healthy.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = Carbon::now()->subDays($days);

        $count = AuditLog::where('created_at', '<', $date)->delete();

        $this->info("Pruned {$count} audit logs older than {$days} days.");
    }
}
