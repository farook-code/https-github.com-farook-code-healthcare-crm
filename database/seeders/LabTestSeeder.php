<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabTest;

class LabTestSeeder extends Seeder
{
    public function run()
    {
        $tests = [
            ['name' => 'Complete Blood Count (CBC)', 'code' => 'LAB-001', 'price' => 25.00, 'category' => 'Hematology'],
            ['name' => 'Lipid Profile', 'code' => 'LAB-002', 'price' => 40.00, 'category' => 'Biochemistry'],
            ['name' => 'Blood Glucose (Fasting)', 'code' => 'LAB-003', 'price' => 15.00, 'category' => 'Biochemistry'],
            ['name' => 'HbA1c', 'code' => 'LAB-004', 'price' => 20.00, 'category' => 'Biochemistry'],
            ['name' => 'Liver Function Test (LFT)', 'code' => 'LAB-005', 'price' => 45.00, 'category' => 'Biochemistry'],
            ['name' => 'Kidney Function Test (KFT)', 'code' => 'LAB-006', 'price' => 45.00, 'category' => 'Biochemistry'],
            ['name' => 'Thyroid Profile (T3, T4, TSH)', 'code' => 'LAB-007', 'price' => 50.00, 'category' => 'Hormones'],
            ['name' => 'Urine Analysis', 'code' => 'LAB-008', 'price' => 10.00, 'category' => 'Clinical Pathology'],
            ['name' => 'X-Ray Chest PA View', 'code' => 'RAD-001', 'price' => 30.00, 'category' => 'Radiology'],
            ['name' => 'Ultrasound Abdomen', 'code' => 'RAD-002', 'price' => 60.00, 'category' => 'Radiology'],
            ['name' => 'MRI Brain', 'code' => 'RAD-003', 'price' => 250.00, 'category' => 'Radiology'],
            ['name' => 'CT Scan Head', 'code' => 'RAD-004', 'price' => 120.00, 'category' => 'Radiology'],
        ];

        foreach ($tests as $test) {
            LabTest::firstOrCreate(
                ['code' => $test['code']],
                $test
            );
        }
    }
}
