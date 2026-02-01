<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\DoctorProfile;
use App\Models\Patient;
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
            DB::beginTransaction();

            // 0. Branches (New Feature)
            $branches = [
                ['name' => 'Main Headquarter', 'slug' => 'main-hq', 'address' => '123 Medical Blvd, New York, NY', 'is_main' => true],
                ['name' => 'Downtown Clinic', 'slug' => 'downtown-clinic', 'address' => '456 Broadway, New York, NY', 'is_main' => false],
            ];
            
            $createdBranches = [];
            foreach ($branches as $b) {
                // Use updateOrCreate to avoid duplicates
                $createdBranches[] = \App\Models\Branch::updateOrCreate(['slug' => $b['slug']], $b);
            }

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
                // Distribute doctors between branches
                $branchId = $createdBranches[$index % 2]->id; 

                $user = User::firstOrCreate(
                    ['email' => strtolower(str_replace([' ', '.'], '', $name)) . '@healthcare.com'],
                    [
                        'name' => $name,
                        'password' => Hash::make('password'),
                        'role_id' => $doctorRole->id,
                        'status' => true,
                        'branch_id' => $branchId // Assign Branch
                    ]
                );
                
                // Be safe - ensure branch is set if user existed
                if(!$user->branch_id) {
                    $user->update(['branch_id' => $branchId]);
                }

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
                ['name' => 'Nurse Jackie', 'role' => $nurseRole, 'email' => 'nurse1@healthcare.com', 'branch' => 0],
                ['name' => 'Carla Espinosa', 'role' => $nurseRole, 'email' => 'nurse2@healthcare.com', 'branch' => 1],
                ['name' => 'Pam Beesly', 'role' => $receptionRole, 'email' => 'reception1@healthcare.com', 'branch' => 0],
                ['name' => 'Receptionist Erin', 'role' => $receptionRole, 'email' => 'reception2@healthcare.com', 'branch' => 1],
            ];

            foreach ($staff as $s) {
                User::updateOrCreate(
                    ['email' => $s['email']],
                    [
                        'name' => $s['name'],
                        'password' => Hash::make('password'),
                        'role_id' => $s['role']->id,
                        'status' => true,
                        'branch_id' => $createdBranches[$s['branch']]->id // Assign Branch
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

                // Create Profile
                Patient::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'patient_code' => 'PT-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                        'dob' => Carbon::now()->subYears(rand(20, 60))->format('Y-m-d'),
                        'gender' => ['male', 'female'][rand(0, 1)],
                        'phone' => '555-010' . rand(0, 9),
                        'address' => '123 Fake St, City',
                    ]
                );
            }

            // 5. Create Appointments (Historical & Unique)
            // Some finished, some scheduled
            foreach ($createdPatients as $pIndex => $patient) {
                // Check if patient already has appointments to prevent duplicates on re-run
                if (Appointment::where('patient_id', $patient->id)->exists()) {
                    continue;
                }
                
                $pModel = Patient::where('user_id', $patient->id)->first();

                // Create 3 appointments per patient
                for ($i = 0; $i < 3; $i++) {
                    $status = ['completed', 'scheduled', 'cancelled'][rand(0, 2)];
                    $dateTime = $status === 'completed' 
                        ? Carbon::now()->subDays(rand(1, 30))->setTime(rand(9, 16), 0)
                        : Carbon::now()->addDays(rand(1, 14))->setTime(rand(9, 16), 0);
                    
                    $doc = $createdDoctors[rand(0, count($createdDoctors) - 1)];

                    $appt = Appointment::create([
                        'patient_id' => $pModel->id, // Use Patient Model ID, NOT User ID
                        'doctor_id' => $doc->id, // User ID of doctor (Appointment model usually expects ID of 'users' table or 'doctors' table? Let's check relation.) 
                        // Wait, Appointment model relation: public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
                        // So User ID is correct.
                        'department_id' => $doc->doctorProfile->department_id,
                        'appointment_date' => $dateTime->toDateString(),
                        'appointment_time' => $dateTime->toTimeString(),
                        'status' => $status,
                    ]);

                    // Create Vitals for completed appointments
                    if ($status === 'completed') {
                        PatientVital::create([
                            'patient_id' => $pModel->id,
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
                            'patient_id' => $pModel->id,
                            'amount' => $doc->doctorProfile->consultation_fee,
                            'status' => 'paid',
                            'issued_at' => $dateTime,
                            'paid_at' => $dateTime,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
        } catch (\Throwable $e) {
            DB::rollBack();
            echo "ERROR SEEDING: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString();
        }
    }
}
