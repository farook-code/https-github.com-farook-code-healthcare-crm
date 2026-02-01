<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'interacting_medicine_id',
        'severity',
        'description',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }

    public function interactingMedicine()
    {
        return $this->belongsTo(Medicine::class, 'interacting_medicine_id');
    }
}
