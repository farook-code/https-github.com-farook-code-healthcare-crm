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
    use HasFactory, Notifiable, \Illuminate\Database\Eloquent\SoftDeletes, \App\Traits\BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'clinic_id',
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

    /**
     * Scope a query to only include users with specific roles.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $roles  string or array
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole($query, $roles)
    {
        if (is_array($roles)) {
            return $query->whereHas('role', function ($q) use ($roles) {
                $q->whereIn('slug', $roles);
            });
        }

        return $query->whereHas('role', function ($q) use ($roles) {
            $q->where('slug', $roles);
        });
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
        return $this->hasManyThrough(Appointment::class, Patient::class, 'user_id', 'patient_id');
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

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('ends_at', '>', now())
            ->latest();
    }

    public function hasFeature($featureKey)
    {
        if ($this->role && $this->role->slug === 'super-admin') {
            return true;
        }

        $subscription = $this->activeSubscription;

        if (!$subscription || !$subscription->plan) {
            return false;
        }

        // Check if the plan has the specific feature key in its capabilities
        // The 'features' column on 'plans' is a JSON array of keys (e.g. ['module_chat', 'limit_doctors_5'])
        $planFeatures = $subscription->plan->features ?? [];

        return in_array($featureKey, $planFeatures);
    }
}
