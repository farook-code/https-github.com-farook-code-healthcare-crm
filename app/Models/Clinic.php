<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'subdomain',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
