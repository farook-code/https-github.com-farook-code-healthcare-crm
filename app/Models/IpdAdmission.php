<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdAdmission extends Model
{
    protected $fillable = [
        'patient_id', 'doctor_id', 'bed_id', 'admission_date', 
        'discharge_date', 'reason_for_admission', 'diagnosis', 
        'status', 'discharge_notes', 'total_estimate', 'advance_payment'
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
        'total_estimate' => 'decimal:2',
        'advance_payment' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }
}
