<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'diagnosis_id',
        'medicine_name',
        'dosage',
        'duration',
        'instructions',
    ];

    public function diagnosis()
    {
        return $this->belongsTo(\App\Models\Diagnosis::class);
    }


}
