<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure role exists first (RoleSeeder should handle this, but for safety)
        $role = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Administrator']
        );

        User::firstOrCreate(
            ['email' => 'superadmin@healthcare.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // or 'superpassword'
                'role_id' => $role->id,
                'status' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
