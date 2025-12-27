<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id', 
        'name', 
        'code', 
        'type',
        'capacity_limit_m3', 
        'is_dg_allowed',
        'total_area_sqm',
        'map_coordinates'
    ];

    protected $casts = [
        'map_coordinates' => 'array'
    ];

    public function leases()
    {
        return $this->hasMany(SpatialLease::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(CargoItem::class);
    }

    // Helper to calculate utilization
    public function getUtilizationAttribute()
    {
        return $this->items()->sum('volume_m3');
    }

    public function getCurrentUtilizationM3Attribute()
    {
        return $this->utilization;
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->capacity_limit_m3 <= 0) return 0;
        return ($this->utilization / $this->capacity_limit_m3) * 100;
    }
}
