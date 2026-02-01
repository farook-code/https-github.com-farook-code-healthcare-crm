<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtBooking extends Model
{
    protected $fillable = [
        'operation_theater_id', 'patient_id', 'lead_surgeon_id',
        'scheduled_start', 'scheduled_end', 'procedure_name',
        'priority', 'status', 'notes'
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
    ];

    public function theater()
    {
        return $this->belongsTo(OperationTheater::class, 'operation_theater_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function surgeon()
    {
        return $this->belongsTo(User::class, 'lead_surgeon_id');
    }
}
