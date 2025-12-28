<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilotageRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_call_id',
        'pilot_id',
        'service_type',
        'status',
        'requested_time',
        'scheduled_time',
        'actual_start',
        'actual_end',
        'boarding_point',
        'weather_condition',
        'calculated_fee',
        'notes',
    ];

    protected $casts = [
        'requested_time' => 'datetime',
        'scheduled_time' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'calculated_fee' => 'decimal:2',
    ];

    // Relationships
    public function portCall()
    {
        return $this->belongsTo(PortCall::class);
    }

    public function pilot()
    {
        return $this->belongsTo(Pilot::class);
    }

    // Helper Methods
    public function calculateFee()
    {
        if (!$this->actual_start || !$this->actual_end || !$this->pilot) {
            return 0;
        }

        $hours = $this->actual_end->diffInHours($this->actual_start);
        $baseFee = $hours * $this->pilot->rate_per_hour;

        // Add surcharges
        $surcharge = 0;
        
        // Night surcharge (22:00 - 06:00) = 50% extra
        if ($this->actual_start->hour >= 22 || $this->actual_start->hour < 6) {
            $surcharge += $baseFee * 0.5;
        }

        // Bad weather surcharge = 30% extra
        if (in_array($this->weather_condition, ['rough', 'stormy', 'poor_visibility'])) {
            $surcharge += $baseFee * 0.3;
        }

        $totalFee = $baseFee + $surcharge;
        
        $this->update(['calculated_fee' => $totalFee]);
        
        return $totalFee;
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'actual_end' => now(),
        ]);
        
        $this->calculateFee();
        
        // Free up pilot
        if ($this->pilot) {
            $this->pilot->update(['status' => 'available']);
        }
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['requested', 'assigned']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }
}
