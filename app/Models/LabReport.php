<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'uploaded_by',
        'title',
        'file_path',
        'file_type',
    ];

    public function appointment()
    {
        return $this->belongsTo(App\Models\Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(App\Models\Patient::class);
    }

    public function uploader()
    {
        return $this->belongsTo(App\Models\User::class, 'uploaded_by');
    }
}
