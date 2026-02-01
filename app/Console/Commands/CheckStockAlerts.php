<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckStockAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-stock-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking stock levels...');

        // Fetch using Query Monitor or just raw if scoping is an issue, 
        // but since this is command, we want ALL medicines from ALL clinics.
        // We need to bypass TenantScope if we want to run this globally for all clinics.
        
        $medicines = \App\Models\Medicine::withoutGlobalScope(\App\Scopes\TenantScope::class)
            ->where('stock_quantity', '<=', 5)
            ->get();

        foreach ($medicines as $med) {
            \App\Services\AlertService::trigger(
                'warning',
                "Low Stock Alert: {$med->name} (SKU: {$med->sku}) is at {$med->stock_quantity} units.",
                $med
            );
            $this->info("Alert triggered for {$med->name} (Clinic ID: {$med->clinic_id})");
        }

        $this->info('Stock check complete.');
    }
}
