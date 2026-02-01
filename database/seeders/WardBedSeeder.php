<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ward;
use App\Models\Bed;

class WardBedSeeder extends Seeder
{
    public function run()
    {
        $wards = [
            ['name' => 'General Ward A', 'type' => 'general', 'capacity' => 10, 'base_price' => 50],
            ['name' => 'General Ward B', 'type' => 'general', 'capacity' => 10, 'base_price' => 50],
            ['name' => 'Private Ward', 'type' => 'private', 'capacity' => 5, 'base_price' => 150],
            ['name' => 'ICU', 'type' => 'icu', 'capacity' => 5, 'base_price' => 500],
        ];

        foreach ($wards as $w) {
            $ward = Ward::firstOrCreate(
                ['name' => $w['name']],
                [
                    'type' => $w['type'],
                    'description' => "Standard {$w['type']} ward environment."
                ]
            );

            // Create Beds
            for ($i = 1; $i <= $w['capacity']; $i++) {
                Bed::firstOrCreate(
                    [
                        'ward_id' => $ward->id,
                        'bed_number' => strtoupper(substr($w['type'], 0, 3)) . '-' . $ward->id . '-' . str_pad($i, 2, '0', STR_PAD_LEFT)
                    ],
                    [
                        'daily_charge' => $w['base_price'],
                        'is_available' => true,
                        'status' => 'available'
                    ]
                );
            }
        }
    }
}
