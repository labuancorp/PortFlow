<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'color_code',
        'specific_gravity',
        'hazard_class',
        'requires_cleaning',
    ];

    protected $casts = [
        'specific_gravity' => 'decimal:4',
        'requires_cleaning' => 'boolean',
    ];
}
