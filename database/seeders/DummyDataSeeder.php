<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use App\Models\Appointment;
use App\Models\PatientVital;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        try {
            // 1. Departments
            $departments = ['Cardiology', 'Neurology', 'Pediatrics', 'Orthopedics', 'Dermatology', 'General Medicine'];
            $deptIds = [];
            foreach ($departments as $deptName) {
                $dept = Department::firstOrCreate(['name' => $deptName]);
                $deptIds[] = $dept->id;
            }

            // Roles
            $adminRole = Role::where('slug', 'admin')->first();
            $doctorRole = Role::where('slug', 'doctor')->first();
            $nurseRole = Role::where('slug', 'nurse')->first();
            $receptionRole = Role::where('slug', 'reception')->first();
            $patientRole = Role::where('slug', 'patient')->first();

            // 2. Create Doctors
            $doctorNames = ['Dr. Sarah Connor', 'Dr. Gregory House', 'Dr. Meredith Grey', 'Dr. Stephen Strange', 'Dr. John Watson'];
            $createdDoctors = [];

            foreach ($doctorNames as $index => $name) {
                $user = User::firstOrCreate(
                    ['email' => strtolower(str_replace([' ', '.'], '', $name)) . '@healthcare.com'],
                    [
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'role_id' => $doctorRole->id,
                        'status' => true,
                    ]
                );

                // Profile
                DoctorProfile::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'department_id' => $deptIds[$index % count($deptIds)],
                        'specialization' => $departments[$index % count($departments)],
                        'qualification' => 'MBBS, MD',
                        'experience_years' => rand(5, 20),
                        'consultation_fee' => rand(100, 300)
                    ]
                );
                $createdDoctors[] = $user;
            }

            // 3. Create Nurses & Receptionists
            $staff = [
                ['name' => 'Nurse Jackie', 'role' => $nurseRole, 'email' => 'nurse1@healthcare.com'],
                ['name' => 'Carla Espinosa', 'role' => $nurseRole, 'email' => 'nurse2@healthcare.com'],
                ['name' => 'Pam Beesly', 'role' => $receptionRole, 'email' => 'reception1@healthcare.com'],
            ];

            foreach ($staff as $s) {
                User::firstOrCreate(
                    ['email' => $s['email']],
                    [
                        'name' => $s['name'],
                        'password' => Hash::make('password'),
                        'role_id' => $s['role']->id,
                        'status' => true,
                    ]
                );
            }

            // 4. Create Patients
            $patientNames = ['Alice Wonderland', 'Bob Builder', 'Charlie Chaplin', 'David Beckham', 'Eve Adams', 'Frank Sinatra'];
            $createdPatients = [];

            foreach ($patientNames as $name) {
                $user = User::firstOrCreate(
                    ['email' => strtolower(str_replace(' ', '', $name)) . '@example.com'],
                    [
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'role_id' => $patientRole->id,
                        'status' => true,
                    ]
                );
                $createdPatients[] = $user;
            }

            // 5. Create Appointments (Historical & Unique)
            // Some finished, some scheduled
            foreach ($createdPatients as $pIndex => $patient) {
                // Check if patient already has appointments to prevent duplicates on re-run
                if (Appointment::where('patient_id', $patient->id)->exists()) {
                    continue;
                }

                // Create 3 appointments per patient
                for ($i = 0; $i < 3; $i++) {
                    $status = ['completed', 'scheduled', 'cancelled'][rand(0, 2)];
                    $dateTime = $status === 'completed' 
                        ? Carbon::now()->subDays(rand(1, 30))->setTime(rand(9, 16), 0)
                        : Carbon::now()->addDays(rand(1, 14))->setTime(rand(9, 16), 0);
                    
                    $doc = $createdDoctors[rand(0, count($createdDoctors) - 1)];

                    $appt = Appointment::create([
                        'patient_id' => $patient->id,
                        'doctor_id' => $doc->id,
                        'department_id' => $doc->doctorProfile->department_id,
                        'appointment_date' => $dateTime->toDateString(),
                        'appointment_time' => $dateTime->toTimeString(),
                        'status' => $status,
                    ]);

                    // Create Vitals for completed appointments
                    if ($status === 'completed') {
                        PatientVital::create([
                            'patient_id' => $patient->id,
                            'appointment_id' => $appt->id,
                            'weight' => rand(60, 90),
                            'height' => rand(160, 190),
                            'blood_pressure' => rand(110, 130) . '/' . rand(70, 90),
                            'pulse' => rand(60, 100),
                            'temperature' => 36.5 + (rand(0, 10) / 10),
                            'oxygen_level' => rand(95, 99),
                            'notes' => 'Vitals stable',
                            'recorded_by' => $doc->id
                        ]);
                        
                        // Create Invoice
                        Invoice::create([
                            'appointment_id' => $appt->id,
                            'patient_id' => $patient->id,
                            'amount' => $doc->doctorProfile->consultation_fee,
                            'status' => 'paid',
                            'issued_at' => $dateTime,
                            'paid_at' => $dateTime,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            echo "ERROR SEEDING: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString();
        }
    }
}
