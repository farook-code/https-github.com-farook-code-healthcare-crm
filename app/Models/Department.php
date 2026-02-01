<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = [
        'clinic_id',
        'name',
        'status',
    ];
}
