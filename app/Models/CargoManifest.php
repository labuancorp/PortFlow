<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoManifest extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_id',
        'agent_id',
        'reference_no',
        'type',
        'status',
        'eta_etd',
        'yard_storage_requested',
        'preferred_zone_type'
    ];

    protected $casts = [
        'eta_etd' => 'datetime',
    ];

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function agent()
    {
        return $this->belongsTo(Organization::class, 'agent_id');
    }

    public function items()
    {
        return $this->hasMany(CargoItem::class);
    }
}
