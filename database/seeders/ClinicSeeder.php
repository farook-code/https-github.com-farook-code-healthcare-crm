<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Default Clinic
        $clinic = Clinic::firstOrCreate(
            ['domain' => 'localhost'], // Assuming local dev
            [
                'name' => 'Main Clinic',
                'subdomain' => 'main',
                'email' => 'admin@mainclinic.com',
                'phone' => '1234567890',
                'address' => '123 Main St',
                'is_active' => true,
            ]
        );

        $clinicId = $clinic->id;
        $this->command->info("Default Clinic ID: $clinicId");

        // 2. Assign ALL existing data to this clinic to prevent "orphans"
        $tables = [
            'users',
            'departments',
            'patients',
            'medicines',
            'appointments',
            'invoices',
            // 'settings', // Settings likely empty or just seeded
            'system_alerts',
            'audit_logs'
        ];

        foreach ($tables as $table) {
            $count = DB::table($table)->whereNull('clinic_id')->update(['clinic_id' => $clinicId]);
            $this->command->info("Updated $count rows in $table");
        }
        
        // 3. Update Settings to belong to this clinic
        // We need to make sure key is unique per clinic_id now, but we haven't updated the unique constraint yet.
        // For now, just assign IDs.
        DB::table('settings')->whereNull('clinic_id')->update(['clinic_id' => $clinicId]);
    }
}
