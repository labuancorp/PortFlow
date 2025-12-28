<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MheEquipment extends Model
{
    protected $fillable = [
        'name',
        'asset_code',
        'type',
        'model',
        'manufacturer',
        'year',
        'status',
        'current_hour_meter',
        'next_pm_due_date',
        'next_pm_due_hours',
        'location'
    ];

    protected $casts = [
        'next_pm_due_date' => 'date',
        'current_hour_meter' => 'decimal:2',
        'next_pm_due_hours' => 'decimal:2',
    ];

    public function maintenanceLogs()
    {
        return $this->hasMany(MheMaintenanceLog::class, 'equipment_id');
    }

    public function bookings()
    {
        return $this->hasMany(MheBooking::class, 'equipment_id');
    }
}
