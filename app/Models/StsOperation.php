<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StsOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'source_vessel_id',
        'receiving_vessel_id',
        'agent_id',
        'cargo_type',
        'quantity',
        'unit',
        'status',
        'requested_time',
        'scheduled_start',
        'actual_start',
        'actual_end',
        'location',
        'safety_zone_radius',
        'weather_condition',
        'wave_height',
        'wind_speed',
        'permit_approved',
        'approved_by',
        'permit_approved_at',
        'calculated_fee',
        'safety_notes',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'safety_zone_radius' => 'decimal:2',
        'wave_height' => 'decimal:2',
        'wind_speed' => 'decimal:2',
        'calculated_fee' => 'decimal:2',
        'permit_approved' => 'boolean',
        'requested_time' => 'datetime',
        'scheduled_start' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'permit_approved_at' => 'datetime',
    ];

    // Relationships
    public function sourceVessel()
    {
        return $this->belongsTo(Vessel::class, 'source_vessel_id');
    }

    public function receivingVessel()
    {
        return $this->belongsTo(Vessel::class, 'receiving_vessel_id');
    }

    public function agent()
    {
        return $this->belongsTo(Organization::class, 'agent_id');
    }

    // Helper Methods
    public function isSafeWeather()
    {
        // Safety criteria for STS operations
        $maxWaveHeight = 1.5; // meters
        $maxWindSpeed = 15; // knots

        return $this->wave_height <= $maxWaveHeight && 
               $this->wind_speed <= $maxWindSpeed;
    }

    public function areVesselsCompatible()
    {
        if (!$this->sourceVessel || !$this->receivingVessel) {
            return false;
        }

        // Check if vessels are of compatible sizes (within 50% GRT difference)
        $grtDifference = abs($this->sourceVessel->grt - $this->receivingVessel->grt);
        $averageGRT = ($this->sourceVessel->grt + $this->receivingVessel->grt) / 2;
        
        return ($grtDifference / $averageGRT) <= 0.5;
    }

    public function calculateFee()
    {
        if (!$this->quantity) {
            return 0;
        }

        // Base fee: RM 5 per ton
        $baseFee = $this->quantity * 5;

        // Add surcharges
        $surcharge = 0;
        
        // Hazardous cargo surcharge
        if (in_array($this->cargo_type, ['lng', 'lpg', 'chemicals'])) {
            $surcharge += $baseFee * 0.5; // 50% extra for hazardous
        }

        // Night operation surcharge
        if ($this->actual_start && ($this->actual_start->hour >= 22 || $this->actual_start->hour < 6)) {
            $surcharge += $baseFee * 0.4; // 40% extra for night ops
        }

        // Large quantity surcharge (>1000 tons)
        if ($this->quantity > 1000) {
            $surcharge += 5000; // Fixed RM 5000 for large transfers
        }

        $totalFee = $baseFee + $surcharge;
        
        $this->update(['calculated_fee' => $totalFee]);
        
        return $totalFee;
    }

    public function approvePermit($approverName)
    {
        if (!$this->isSafeWeather()) {
            return false; // Cannot approve in unsafe weather
        }

        if (!$this->areVesselsCompatible()) {
            return false; // Cannot approve incompatible vessels
        }

        $this->update([
            'permit_approved' => true,
            'approved_by' => $approverName,
            'permit_approved_at' => now(),
            'status' => 'approved',
        ]);

        return true;
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'actual_end' => now(),
        ]);
        
        $this->calculateFee();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('permit_approved', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->reference_number) {
                $model->reference_number = 'STS-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }
}
