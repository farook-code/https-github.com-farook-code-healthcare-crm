<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabReport;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Patient;

class LabReportSeeder extends Seeder
{
    public function run()
    {
        $appointments = Appointment::with(['patient'])->limit(15)->get();
        $staff = User::whereHas('role', function($q) {
            $q->whereIn('slug', ['doctor', 'lab_technician', 'admin']);
        })->first();

        if(!$staff) {
             // Fallback
             $staff = User::first();
        }

        foreach($appointments as $app) {
            // Check if patient profile exists
            $patientProfile = $app->patient;
            if(!$patientProfile) continue;

            LabReport::create([
                'appointment_id' => $app->id,
                'patient_id' => $patientProfile->id,
                'uploaded_by' => $staff->id,
                'title' => 'Blood Test Report - ' . $app->appointment_date->format('M d'),
                'file_path' => 'dummy/report.pdf', // Dummy path
                'file_type' => 'pdf',
                'status' => 'completed',
                'generated_at' => now(),
            ]);

            LabReport::create([
                'appointment_id' => $app->id,
                'patient_id' => $patientProfile->id,
                'uploaded_by' => $staff->id,
                'title' => 'Urinalysis - ' . $app->appointment_date->format('M d'),
                'file_path' => 'dummy/urine.pdf', // Dummy path
                'file_type' => 'pdf',
                'status' => 'completed',
                'generated_at' => now(),
            ]);
        }
    }
}
