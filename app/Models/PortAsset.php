<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'identifier',
        'rate_per_hour',
        'rate_per_day',
        'status',
        'description',
        'last_maintenance_date',
        'next_maintenance_date',
        'safety_cert_expiry',
        'telemetry_id',
        'current_engine_hours',
        'billing_mode'
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'safety_cert_expiry' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(AssetBooking::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(AssetBooking::class)->whereIn('status', ['approved', 'active'])->latestOfMany();
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(AssetMaintenanceLog::class);
    }
}
