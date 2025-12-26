<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'total_capacity_m3'];

    public function zones()
    {
        return $this->hasMany(WarehouseZone::class);
    }
}
