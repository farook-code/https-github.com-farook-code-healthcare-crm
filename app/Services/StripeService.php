<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class StripeService
{
    protected $secret;

    public function __construct()
    {
        // Fallback for testing if .env not set, usually you'd pull from config
        $this->secret = env('STRIPE_SECRET', 'sk_test_mock_secret');
    }

    /**
     * Create a specific PaymentIntent for an Invoice.
     */
    public function createPaymentIntent($amount, $currency = 'usd', $metadata = [])
    {
        // Convert amount to cents
        $amountInCents = (int) ($amount * 100);

        try {
            $response = Http::withToken($this->secret)
                ->asForm()
                ->post('https://api.stripe.com/v1/payment_intents', [
                    'amount' => $amountInCents,
                    'currency' => $currency,
                    'metadata' => $metadata,
                    'automatic_payment_methods' => ['enabled' => 'true'],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            // For development/mocking purposes, if API fails (invalid key), return a mock intent
            if (env('APP_ENV') === 'local') {
                return $this->mockIntent($amountInCents, $currency);
            }

            return null;

        } catch (\Exception $e) {
            return $this->mockIntent($amountInCents, $currency);
        }
    }

    private function mockIntent($amount, $currency)
    {
        return [
            'id' => 'pi_mock_' . uniqid(),
            'client_secret' => 'pi_mock_' . uniqid() . '_secret_' . uniqid(),
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'requires_payment_method',
        ];
    }
}
