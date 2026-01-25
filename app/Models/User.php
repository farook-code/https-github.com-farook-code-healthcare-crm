<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Department;
use App\Models\DoctorProfile;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'name',
    'email',
    'password',
   'role_id',
   'department_id',
    'status',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
   protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
    ];
}

    public function role()
{
    return $this->belongsTo(Role::class);
}

public function department()
{
    return $this->belongsTo(Department::class);
}

public function doctorProfile()
{
    return $this->hasOne(DoctorProfile::class);
}

public function patient()
{
    return $this->hasOne(Patient::class);
}


    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function getPatientCodeAttribute()
    {
        return $this->patient?->patient_code;
    }

    public function getGenderAttribute() { return $this->patient?->gender; }
    public function getDobAttribute() { return $this->patient?->dob; }
    public function getPhoneAttribute() { return $this->patient?->phone; }

    public function getInsuranceProviderAttribute() { return $this->patient?->insurance_provider; }
    public function getPolicyNumberAttribute() { return $this->patient?->policy_number; }

    // Medical Attributes Delegation
    public function getAllergiesAttribute() { return $this->patient?->allergies; }
    public function getChronicConditionsAttribute() { return $this->patient?->chronic_conditions; }
    public function getCurrentMedicationsAttribute() { return $this->patient?->current_medications; }
}
