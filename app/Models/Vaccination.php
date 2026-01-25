<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    protected $fillable = [
        'patient_id',
        'vaccine_name',
        'dose_number',
        'administered_date',
        'notes',
    ];

    protected $casts = [
        'administered_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
