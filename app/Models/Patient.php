<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_code',
        'name',
        'gender',
        'dob',
        'blood_group',
        'phone',
        'email',
        'address',
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

}
