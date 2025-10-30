<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_name',
        'clinic_type',
        'specialty',
        'address',
        'phone',
        'rating',
        'distance',
        'zip_code',
    ];
}
