<?php

namespace App\Services;

class InsuranceVerificationService
{
    /**
     * Mock verification of insurance coverage.
     */
    public function verify($provider, $memberId)
    {
        // Simulate network delay
        sleep(1); 

        // Mock external API call logic
        // For demo: effective immediately
        
        // If member ID ends with '99', they are inactive
        if (str_ends_with($memberId, '99')) {
             return [
                'isValid' => false,
                'status' => 'inactive',
                'message' => 'Coverage Terminated or Invalid Member ID.'
            ];
        }

        return [
            'isValid' => true,
            'status' => 'active',
            'payer_name' => $provider,
            'coverage_details' => [
                'plan_type' => 'PPO',
                'coverage_percent' => 80,
                'copay' => 25.00,
                'deductible_remaining' => rand(0, 500)
            ],
            'message' => 'Active Coverage Verified.'
        ];
    }
}
