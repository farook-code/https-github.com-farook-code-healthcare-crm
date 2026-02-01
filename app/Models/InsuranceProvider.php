<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    protected $fillable = ['name', 'contact_number', 'email', 'website', 'address', 'network_type'];

    public function patientInsurances()
    {
        return $this->hasMany(PatientInsurance::class);
    }
}
