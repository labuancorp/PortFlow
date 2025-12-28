<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    protected $fillable = [
        'name',
        'zone_id',
        'capacity_volume',
        'current_volume',
        'current_product_id',
        'status',
        'last_cleaned_at',
        'gis_coordinates',
    ];

    protected $casts = [
        'capacity_volume' => 'decimal:2',
        'current_volume' => 'decimal:2',
        'last_cleaned_at' => 'datetime',
        'gis_coordinates' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(ProductType::class, 'current_product_id');
    }

    public function readings()
    {
        return $this->hasMany(TankReading::class);
    }
}
