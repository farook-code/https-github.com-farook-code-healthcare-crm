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
        'outcome',
    ];



public function prescriptions()
{
    return $this->hasMany(\App\Models\Prescription::class);
}

public function appointment()
{
    return $this->belongsTo(\App\Models\Appointment::class);
}

public function doctor()
{
    return $this->belongsTo(\App\Models\User::class, 'doctor_id');
}

public function patient()
{
    return $this->belongsTo(\App\Models\User::class, 'patient_id');
}


}

