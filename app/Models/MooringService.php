<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MooringService extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_call_id',
        'service_type',
        'gang_size',
        'status',
        'requested_time',
        'scheduled_time',
        'actual_start',
        'actual_end',
        'gang_supervisor',
        'supervisor_phone',
        'line_boat_required',
        'calculated_fee',
        'notes',
    ];

    protected $casts = [
        'gang_size' => 'integer',
        'line_boat_required' => 'boolean',
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

    // Helper Methods
    public function calculateFee()
    {
        if (!$this->actual_start || !$this->actual_end) {
            return 0;
        }

        $hours = max(1, $this->actual_end->diffInHours($this->actual_start));
        
        // Base rate per crew member per hour
        $ratePerCrewPerHour = 50; // RM 50 per crew per hour
        $baseFee = $hours * $this->gang_size * $ratePerCrewPerHour;

        // Add surcharges
        $surcharge = 0;
        
        // Night surcharge (22:00 - 06:00) = 50% extra
        if ($this->actual_start->hour >= 22 || $this->actual_start->hour < 6) {
            $surcharge += $baseFee * 0.5;
        }

        // Line boat fee
        if ($this->line_boat_required) {
            $surcharge += 300 * $hours; // RM 300 per hour for line boat
        }

        // Weekend/holiday surcharge = 30% extra
        if ($this->actual_start->isWeekend()) {
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
