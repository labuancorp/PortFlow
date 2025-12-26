<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseZone extends Model
{
    use HasFactory;

    protected $fillable = ['warehouse_id', 'name', 'code', 'capacity_limit_m3', 'is_dg_allowed'];

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
        $used = $this->items()->sum('volume_m3');
        return $used;
    }

    public function getUtilizationPercentageAttribute()
    {
        if ($this->capacity_limit_m3 <= 0) return 0;
        return ($this->utilization / $this->capacity_limit_m3) * 100;
    }
}
