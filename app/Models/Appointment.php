<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Appointment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'appointment_time',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * Medical patient (NOT user)
     */
    public function patient()
{
    return $this->belongsTo(\App\Models\User::class, 'patient_id');
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

public function vitals()
{
    return $this->hasMany(PatientVital::class);
}

public function labReports()
{
    return $this->hasMany(\App\Models\LabReport::class);
}

}
