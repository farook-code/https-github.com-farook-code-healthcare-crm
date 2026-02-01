<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;
use App\Models\Bed;

class WardSeeder extends Seeder
{
    public function run()
    {
        // 1. General Ward
        $general = Ward::firstOrCreate([
            'name' => 'General Ward',
            'type' => 'General',
            'floor_number' => '1',
            'description' => 'Standard shared ward for stability monitoring.'
        ]);

        $this->createBeds($general, 10, 'G-', 50.00);

        // 2. ICU
        $icu = Ward::firstOrCreate([
            'name' => 'ICU',
            'type' => 'ICU',
            'floor_number' => '2',
            'description' => 'Intensive Care Unit with advanced monitoring.'
        ]);

        $this->createBeds($icu, 5, 'ICU-', 500.00);

        // 3. Private Ward
        $private = Ward::firstOrCreate([
            'name' => 'Private Suites',
            'type' => 'Private',
            'floor_number' => '3',
            'description' => 'Private rooms with attached bath and guest bed.'
        ]);

        $this->createBeds($private, 5, 'P-', 200.00);
    }

    private function createBeds($ward, $count, $prefix, $price)
    {
        for ($i = 1; $i <= $count; $i++) {
            Bed::firstOrCreate([
                'ward_id' => $ward->id,
                'bed_number' => $prefix . str_pad($i, 2, '0', STR_PAD_LEFT)
            ], [
                'daily_charge' => $price,
                'is_available' => true,
                'status' => 'available'
            ]);
        }
    }
}
