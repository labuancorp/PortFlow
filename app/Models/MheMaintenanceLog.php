<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MheMaintenanceLog extends Model
{
    protected $fillable = [
        'equipment_id',
        'type',
        'description',
        'parts_cost',
        'labor_cost',
        'service_date',
        'technician_name',
        'meter_reading'
    ];

    protected $casts = [
        'service_date' => 'datetime',
        'parts_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'meter_reading' => 'decimal:2',
    ];

    public function equipment()
    {
        return $this->belongsTo(MheEquipment::class, 'equipment_id');
    }
}
