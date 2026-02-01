<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'clinic_id',
        'user_id',
        'patient_code',
        'name',
        'gender',
        'dob',
        'blood_group',
        'phone',
        'email',
        'address',
        'insurance_provider',
        'policy_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'allergies',
        'chronic_conditions',
        'current_medications',
        'status',
    ];

    /* =========================
        RELATIONSHIPS
    ========================== */

    /**
     * Login owner (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Patient appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /* =========================
        SCOPES (Future-safe)
    ========================== */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function vitals()
    {
        return $this->hasMany(PatientVital::class);
    }

    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

}
