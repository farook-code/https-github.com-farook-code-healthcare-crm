<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'clinic_id',
        'name',
        'generic_name',
        'sku',
        'description',
        'price',
        'stock_quantity',
        'unit',
        'expiry_date',
        'manufacturer'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function interactions()
    {
        return $this->hasMany(MedicineInteraction::class, 'medicine_id');
    }
}
