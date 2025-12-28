<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TowageRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_call_id',
        'tugboat_id',
        'service_type',
        'tugboats_required',
        'status',
        'requested_time',
        'scheduled_time',
        'actual_start',
        'actual_end',
        'from_location',
        'to_location',
        'weather_condition',
        'calculated_fee',
        'notes',
    ];

    protected $casts = [
        'tugboats_required' => 'integer',
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

    public function tugboat()
    {
        return $this->belongsTo(Tugboat::class);
    }

    // Helper Methods
    public function calculateFee()
    {
        if (!$this->actual_start || !$this->actual_end || !$this->tugboat) {
            return 0;
        }

        $hours = max(1, $this->actual_end->diffInHours($this->actual_start));
        $baseFee = $hours * $this->tugboat->rate_per_hour * $this->tugboats_required;

        // Add surcharges
        $surcharge = 0;
        
        // Night surcharge (22:00 - 06:00) = 50% extra
        if ($this->actual_start->hour >= 22 || $this->actual_start->hour < 6) {
            $surcharge += $baseFee * 0.5;
        }

        // Bad weather surcharge = 40% extra (higher than pilotage due to risk)
        if (in_array($this->weather_condition, ['rough', 'stormy', 'poor_visibility'])) {
            $surcharge += $baseFee * 0.4;
        }

        // Escort service premium = 25% extra
        if ($this->service_type === 'escort') {
            $surcharge += $baseFee * 0.25;
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
        
        // Free up tugboat
        if ($this->tugboat) {
            $this->tugboat->update(['status' => 'available']);
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
