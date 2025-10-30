<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostTransparency extends Model
{
    protected $table = 'cost_transparency';
    protected $fillable = ['service_name', 'min_price', 'max_price'];
    public $timestamps = false;
}
