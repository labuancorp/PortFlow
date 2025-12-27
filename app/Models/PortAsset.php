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
        'description'
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
