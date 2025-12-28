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
        'current_location',
        'received_at',
        'discharged_at'
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'discharged_at' => 'datetime',
    ];

    public function manifest()
    {
        return $this->belongsTo(CargoManifest::class, 'cargo_manifest_id');
    }

    public function zone()
    {
        return $this->belongsTo(WarehouseZone::class, 'warehouse_zone_id');
    }

    /**
     * Accessor to check if cargo is dangerous goods
     */
    public function getIsDgCargoAttribute()
    {
        return !empty($this->dg_class);
    }
}
