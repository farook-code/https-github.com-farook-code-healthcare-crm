<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired subscriptions and update their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired subscriptions...');

        $expired = Subscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->update(['status' => 'expired']);

        if ($expired > 0) {
            $this->info("Expired {$expired} subscriptions.");
            Log::info("Expired {$expired} subscriptions via scheduled task.");
            
            // Here you could trigger an event to send emails:
            // event(new SubscriptionExpired($subscription));
            
        } else {
            $this->info('No active subscriptions have expired.');
        }
    }
}
