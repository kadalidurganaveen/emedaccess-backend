<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'prescriptions';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'pharmacy',
        'type',
        'price',
        'discount',
    ];

    // Optional: If you want timestamps (created_at, updated_at) enabled
    public $timestamps = true;
}
