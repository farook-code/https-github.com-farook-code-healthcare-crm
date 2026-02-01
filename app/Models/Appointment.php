<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Appointment extends Model
{
    use LogsActivity, \Illuminate\Database\Eloquent\SoftDeletes, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'appointment_time',
        'status',
        'reason',
        'type',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * Medical patient (NOT user)
     */
    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class, 'patient_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Doctor user
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function diagnosis()
    {
        return $this->hasOne(\App\Models\Diagnosis::class);
    }
    
    // Alias for code compatibility
    public function validDiagnosis()
    {
        return $this->hasOne(\App\Models\Diagnosis::class);
    }

public function vitals()
{
    return $this->hasMany(PatientVital::class);
}

public function labReports()
{
    return $this->hasMany(\App\Models\LabReport::class);
}

}
