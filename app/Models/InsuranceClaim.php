<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'patient_id',
        'claim_number',
        'provider_name',
        'amount_claimed',
        'amount_approved',
        'status',
        'rejection_reason',
        'submitted_at',
        'responded_at',
        'notes',
    ];

    protected $casts = [
        'submitted_at' => 'date',
        'responded_at' => 'date',
        'amount_claimed' => 'decimal:2',
        'amount_approved' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
