<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Administrator', 'slug' => 'super-admin'],
            ['name' => 'Administrator', 'slug' => 'admin'],
            ['name' => 'Doctor', 'slug' => 'doctor'],
            ['name' => 'Nurse', 'slug' => 'nurse'],
            ['name' => 'Reception', 'slug' => 'reception'],
            ['name' => 'Patient', 'slug' => 'patient'],
            ['name' => 'Pharmacist', 'slug' => 'pharmacist'],
            ['name' => 'Lab Technician', 'slug' => 'lab_technician'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
