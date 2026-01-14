<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed roles first
        $this->call(RoleSeeder::class);

        // 2. Create Admin user
        User::firstOrCreate(
            ['email' => 'admin@healthcare.com'],
            [
                'name' => 'System Admin',
                'password' => bcrypt('password'),
                'role_id' => Role::where('slug', 'admin')->first()->id,
            ]
        );

        // 3. Create Doctor user
        User::firstOrCreate(
            ['email' => 'doctor@healthcare.com'],
            [
                'name' => 'Dr John Smith',
                'password' => bcrypt('password'),
                'role_id' => Role::where('slug', 'doctor')->first()->id,
            ]
        );

        // 4. Create Patient user
        User::firstOrCreate(
            ['email' => 'patient@healthcare.com'],
            [
                'name' => 'Sample Patient',
                'password' => bcrypt('password'),
                'role_id' => Role::where('slug', 'patient')->first()->id,
            ]
        );
    }
}
