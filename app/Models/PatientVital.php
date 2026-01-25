<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientVital extends Model
{
    protected $fillable = [
        'patient_id',
        'appointment_id',
        'recorded_by',
        'height',
        'weight',
        'blood_pressure',
        'pulse',
        'temperature',
        'oxygen_level',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
