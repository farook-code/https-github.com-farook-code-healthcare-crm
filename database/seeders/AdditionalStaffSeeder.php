<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Action;

class AdditionalStaffSeeder extends Seeder
{
    public function run()
    {
        $pharmaRole = Role::where('slug', 'pharmacist')->first();
        $labRole = Role::where('slug', 'lab_technician')->first();

        // Ensure roles exist if seeder wasn't run separately
        if(!$pharmaRole) {
            $pharmaRole = Role::create(['name' => 'Pharmacist', 'slug' => 'pharmacist']);
        }
        if(!$labRole) {
            $labRole = Role::create(['name' => 'Lab Technician', 'slug' => 'lab_technician']);
        }

        if($pharmaRole) {
            User::firstOrCreate(
                ['email' => 'pharma@healthcare.com'],
                [
                    'name' => 'Pharmacist Phil',
                    'password' => Hash::make('password'),
                    'role_id' => $pharmaRole->id,
                    'status' => true,
                    'branch_id' => 1
                ]
            );
        }

        if($labRole) {
            User::firstOrCreate(
                ['email' => 'lab@healthcare.com'],
                [
                    'name' => 'Lab Tech Larry',
                    'password' => Hash::make('password'),
                    'role_id' => $labRole->id,
                    'status' => true,
                    'branch_id' => 1
                ]
            );
        }
    }
}
