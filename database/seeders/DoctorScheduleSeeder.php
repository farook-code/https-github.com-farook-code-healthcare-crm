<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\DoctorSchedule;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = User::whereHas('role', function($q){
            $q->where('slug', 'doctor');
        })->get();

        foreach($doctors as $doctor) {
            // Default: Mon-Fri 09:00 - 17:00
            DoctorSchedule::updateOrCreate(
                ['doctor_id' => $doctor->id],
                ['working_hours' => [
                    'monday'    => ['09:00', '17:00'],
                    'tuesday'   => ['09:00', '17:00'],
                    'wednesday' => ['09:00', '17:00'],
                    'thursday'  => ['09:00', '17:00'],
                    'friday'    => ['09:00', '17:00'],
                    'saturday'  => null, // OFF
                    'sunday'    => null, // OFF
                ]]
            );
        }
    }
}
