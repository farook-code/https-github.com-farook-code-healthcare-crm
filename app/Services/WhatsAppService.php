<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl = 'https://graph.facebook.com/v19.0';
    protected $phoneNumberId;
    protected $accessToken;

    public function __construct()
    {
        $this->phoneNumberId = Settings::get('whatsapp_phone_number_id');
        $this->accessToken = Settings::get('whatsapp_access_token');
    }

    /**
     * Send a template message.
     *
     * @param string $to Recipient phone number (with country code)
     * @param string $templateName Name of the template in Meta Business Manager
     * @param string $languageCode Language code (e.g., en_US)
     * @param array $components Components for variable substitution
     * @return array|bool Response or false on failure
     */
    public function sendTemplate(string $to, string $templateName, string $languageCode = 'en_US', array $components = [])
    {
        if (!$this->isConfigured()) {
            Log::warning('WhatsApp API not configured.');
            return false;
        }

        $url = "{$this->baseUrl}/{$this->phoneNumberId}/messages";

        try {
            $response = Http::withToken($this->accessToken)
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => ['code' => $languageCode],
                        'components' => $components
                    ]
                ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('WhatsApp Send Error: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Exception: ' . $e->getMessage());
            return false;
        }
    }

    public function isConfigured(): bool
    {
        return !empty($this->phoneNumberId) && !empty($this->accessToken);
    }
}
