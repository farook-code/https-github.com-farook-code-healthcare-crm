<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendWhatsAppMessage implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $phone,
        public string $templateName,
        public string $languageCode = 'en_US'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = new \App\Services\WhatsAppService();
        if ($service->isConfigured()) {
            $service->sendTemplate($this->phone, $this->templateName, $this->languageCode);
        }
    }
}
