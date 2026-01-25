<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Invoice extends Model
{
    use LogsActivity;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'amount',
        'status',
        'issued_at',
        'paid_at',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'paid_at'   => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function insuranceClaim()
    {
        return $this->hasOne(InsuranceClaim::class);
    }
}
