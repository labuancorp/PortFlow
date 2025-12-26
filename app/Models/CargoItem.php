<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cargo_manifest_id',
        'tracking_number',
        'description',
        'weight_kg',
        'volume_m3',
        'dg_class',
        'status',
        'current_location'
    ];

    public function manifest()
    {
        return $this->belongsTo(CargoManifest::class, 'cargo_manifest_id');
    }

    public function zone()
    {
        return $this->belongsTo(WarehouseZone::class, 'warehouse_zone_id');
    }
}
