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
        'asset_code',
        'model',
        'manufacturer',
        'year',
        'location',
        'rate_per_hour',
        'rate_per_day',
        'status',
        'description',
        'last_maintenance_date',
        'next_maintenance_date',
        'next_pm_due_hours',
        'safety_cert_expiry',
        'telemetry_id',
        'current_engine_hours',
        'billing_mode'
    ];

    protected $casts = [
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'safety_cert_expiry' => 'date',
        'current_engine_hours' => 'decimal:2',
        'next_pm_due_hours' => 'decimal:2',
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

    // Helper method to check if asset is MHE type
    public function isMHE()
    {
        return in_array($this->type, ['forklift', 'crane', 'reach_stacker', 'terminal_tractor', 'container_handler']);
    }

    // Helper method to check PM status
    public function isPMDue()
    {
        if ($this->isMHE() && $this->next_pm_due_hours && $this->current_engine_hours) {
            return $this->current_engine_hours >= $this->next_pm_due_hours;
        }
        return false;
    }

    // Get PM progress percentage
    public function getPMProgress()
    {
        if ($this->isMHE() && $this->next_pm_due_hours && $this->current_engine_hours) {
            return min(100, ($this->current_engine_hours / $this->next_pm_due_hours) * 100);
        }
        return 0;
    }
}
