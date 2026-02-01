<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = ['doctor_id', 'working_hours'];

    protected $casts = [
        'working_hours' => 'array',
    ];
}
