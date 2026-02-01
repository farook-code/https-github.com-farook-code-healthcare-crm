<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationTheater extends Model
{
    protected $table = 'operation_theaters';
    
    protected $fillable = ['name', 'type', 'status', 'hourly_charge'];

    protected $casts = [
        'hourly_charge' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(OtBooking::class);
    }
}
