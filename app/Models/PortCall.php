<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortCall extends Model
{
    protected $fillable = [
        'vessel_id',
        'agent_id',
        'status',
        'eta',
        'etd',
        'ata',
        'atb',
        'atd',
        'assigned_berth_id',
        'reference_no'
    ];

    protected $casts = [
        'eta' => 'datetime',
        'etd' => 'datetime',
        'ata' => 'datetime',
        'atb' => 'datetime',
        'atd' => 'datetime',
    ];

    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }

    public function agent()
    {
        return $this->belongsTo(Organization::class, 'agent_id');
    }

    public function berth()
    {
        return $this->belongsTo(Berth::class, 'assigned_berth_id');
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Phase 6: Advanced Maritime Services
    public function pilotageRequests()
    {
        return $this->hasMany(PilotageRequest::class);
    }

    public function towageRequests()
    {
        return $this->hasMany(TowageRequest::class);
    }

    public function bunkerRequests()
    {
        return $this->hasMany(BunkerRequest::class);
    }

    public function mooringServices()
    {
        return $this->hasMany(MooringService::class);
    }
}
