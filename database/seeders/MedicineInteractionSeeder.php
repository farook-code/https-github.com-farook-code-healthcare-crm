<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineInteraction;
use Illuminate\Database\Seeder;

class MedicineInteractionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Common Medicines if they don't exist
        $aspirin = Medicine::firstOrCreate(['name' => 'Aspirin'], [
            'generic_name' => 'Acetylsalicylic acid',
            'sku' => 'MED-001',
            'description' => 'Pain reliever and blood thinner.',
            'price' => 5.00,
            'stock_quantity' => 100,
            'unit' => 'Pack',
            'expiry_date' => '2027-01-01',
            'manufacturer' => 'Bayer'
        ]);

        $warfarin = Medicine::firstOrCreate(['name' => 'Warfarin'], [
            'generic_name' => 'Warfarin Sodium',
            'sku' => 'MED-002',
            'description' => 'Blood thinner to treat clots.',
            'price' => 15.00,
            'stock_quantity' => 50,
            'unit' => 'Bottle',
            'expiry_date' => '2026-06-01',
            'manufacturer' => 'BMS'
        ]);

        $ibuprofen = Medicine::firstOrCreate(['name' => 'Ibuprofen'], [
            'generic_name' => 'Ibuprofen',
            'sku' => 'MED-003',
            'description' => 'NSAID for pain and fever.',
            'price' => 8.00,
            'stock_quantity' => 200,
            'unit' => 'Strip',
            'expiry_date' => '2028-01-01',
            'manufacturer' => 'Advil'
        ]);

        $lisinopril = Medicine::firstOrCreate(['name' => 'Lisinopril'], [
            'generic_name' => 'Lisinopril',
            'sku' => 'MED-004',
            'description' => 'ACE inhibitor for high blood pressure.',
            'price' => 12.00,
            'stock_quantity' => 80,
            'unit' => 'Pack',
            'expiry_date' => '2026-12-31',
            'manufacturer' => 'Generic'
        ]);

        $potassium = Medicine::firstOrCreate(['name' => 'Potassium Chloride'], [
            'generic_name' => 'Potassium Chloride',
            'sku' => 'MED-005',
            'description' => 'Mineral supplement.',
            'price' => 6.00,
            'stock_quantity' => 100,
            'unit' => 'Bottle',
            'expiry_date' => '2027-05-01',
            'manufacturer' => 'Generic'
        ]);

        // 2. Define Interactions
        $interactions = [
            [
                'medicine_id' => $aspirin->id,
                'interacting_medicine_id' => $warfarin->id,
                'severity' => 'high',
                'description' => 'Taking Aspirin with Warfarin significantly increases the risk of bleeding.'
            ],
            [
                'medicine_id' => $ibuprofen->id,
                'interacting_medicine_id' => $aspirin->id,
                'severity' => 'moderate',
                'description' => 'Ibuprofen may interfere with the antiplatelet effect of low-dose Aspirin.'
            ],
            [
                'medicine_id' => $lisinopril->id,
                'interacting_medicine_id' => $potassium->id,
                'severity' => 'high',
                'description' => 'Combination can cause hyperkalemia (dangerously high potassium levels).'
            ],
             [
                'medicine_id' => $warfarin->id,
                'interacting_medicine_id' => $ibuprofen->id,
                'severity' => 'high',
                'description' => 'NSAIDs like Ibuprofen increase bleeding risk when taken with Warfarin.'
            ],
        ];

        foreach ($interactions as $interaction) {
            MedicineInteraction::firstOrCreate(
                [
                    'medicine_id' => $interaction['medicine_id'],
                    'interacting_medicine_id' => $interaction['interacting_medicine_id']
                ],
                $interaction
            );
        }
    }
}
