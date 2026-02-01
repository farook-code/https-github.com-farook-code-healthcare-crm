<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientInsurance extends Model
{
    protected $fillable = [
        'patient_id', 'insurance_provider_id', 'policy_number', 
        'group_number', 'start_date', 'end_date', 'coverage_limit', 'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'coverage_limit' => 'decimal:2',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function provider()
    {
        return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id');
    }
}
