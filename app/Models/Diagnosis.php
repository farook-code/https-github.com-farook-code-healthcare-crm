<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Prescription;


class Diagnosis extends Model
{
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'symptoms',
        'diagnosis',
        'notes',
    ];



public function prescriptions()
{
    return $this->hasMany(\App\Models\Prescription::class);
}

public function appointment()
{
    return $this->belongsTo(\App\Models\Appointment::class);
}


}

