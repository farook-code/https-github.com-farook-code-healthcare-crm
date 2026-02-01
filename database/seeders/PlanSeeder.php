<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Solo Practice',
                'slug' => 'basic',
                'price' => 49.00,
                'duration_in_days' => 30,
                'features' => [
                    'limit_doctors_1',
                    'limit_patients_unlimited',
                    'module_appointments',
                    'support_email'
                ]
            ],
            [
                'name' => 'Small Clinic',
                'slug' => 'premium',
                'price' => 199.00,
                'duration_in_days' => 30,
                'features' => [
                    'limit_doctors_5',
                    'module_pharmacy_stock',
                    'module_lab_reporting',
                    'module_chat',
                    'module_staff_access',
                    'analytics_advanced'
                ]
            ],
            [
                'name' => 'Hospital',
                'slug' => 'pro',
                'price' => 499.00,
                'duration_in_days' => 30,
                'features' => [
                    'limit_doctors_unlimited',
                    'limit_staff_unlimited',
                    'module_insurance_claims',
                    'module_audit_logs',
                    'module_api_access',
                    'support_priority_247',
                    'custom_white_label'
                ]
            ],
            [
                'name' => 'Medical Network',
                'slug' => 'enterprise',
                'price' => 1499.00,
                'duration_in_days' => 30,
                'features' => [
                    'module_multi_branch',      // <--- NEW FEATURE
                    'limit_branches_unlimited',
                    'centralized_dashboard',
                    'global_patient_records',
                    'limit_doctors_unlimited',
                    'module_insurance_claims',
                    'module_audit_logs',
                    'dedicated_account_manager',
                    'custom_integrations'
                ]
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
