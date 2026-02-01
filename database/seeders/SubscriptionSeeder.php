<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = Plan::all()->keyBy('slug');
        $rolePatient = Role::where('slug', 'patient')->first();
        $roleAdmin = Role::where('slug', 'admin')->first();

        if ($plans->isEmpty()) {
            $this->command->warn('No plans found. Please run PlanSeeder first.');
            return;
        }

        // 1. Create specific test users for each plan
        $users = [
            'basic' => ['email' => 'basic@test.com', 'name' => 'Basic User', 'role_id' => $rolePatient->id],
            'premium' => ['email' => 'premium@test.com', 'name' => 'Premium User', 'role_id' => $rolePatient->id],
            'pro' => ['email' => 'pro@test.com', 'name' => 'Pro User', 'role_id' => $rolePatient->id], // Maybe give Pro to an admin or power user
        ];

        foreach ($users as $slug => $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'role_id' => $userData['role_id'],
                    'status' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Assign Subscription
            if (isset($plans[$slug])) {
                Subscription::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'status' => 'active'
                    ],
                    [
                        'plan_id' => $plans[$slug]->id,
                        'starts_at' => Carbon::now(),
                        'ends_at' => Carbon::now()->addDays(30),
                    ]
                );
            }
        }

        // 2. Assign 'Basic' to the main patient user
        $mainPatient = User::where('email', 'patient@healthcare.com')->first();
        if ($mainPatient && isset($plans['basic'])) {
             Subscription::updateOrCreate(
                ['user_id' => $mainPatient->id, 'status' => 'active'],
                [
                    'plan_id' => $plans['basic']->id,
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addDays(30),
                ]
            );
        }

        // 2.1 Assign 'Basic' to Admin user so they can test Upgrading
        $adminUser = User::where('email', 'admin@healthcare.com')->first();
        if ($adminUser && isset($plans['basic'])) {
             Subscription::updateOrCreate(
                ['user_id' => $adminUser->id, 'status' => 'active'],
                [
                    'plan_id' => $plans['basic']->id,
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addDays(30),
                ]
            );
        }

        // 3. Assign custom 'Expired' subscription for testing
        $expiredUser = User::firstOrCreate(
            ['email' => 'expired@test.com'],
            [
                'name' => 'Expired User',
                'password' => Hash::make('password'),
                'role_id' => $rolePatient->id,
                'status' => true,
            ]
        );

        if (isset($plans['premium'])) {
            Subscription::create([
                'user_id' => $expiredUser->id,
                'plan_id' => $plans['premium']->id,
                'status' => 'expired',
                'starts_at' => Carbon::now()->subDays(60),
                'ends_at' => Carbon::now()->subDays(30),
            ]);
        }
    }
}
