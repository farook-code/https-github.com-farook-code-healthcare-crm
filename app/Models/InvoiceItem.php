<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
        'medicine_id',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
