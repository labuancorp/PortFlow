<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BunkerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_call_id',
        'fuel_type',
        'quantity_requested',
        'unit',
        'status',
        'requested_time',
        'scheduled_delivery',
        'actual_delivery_start',
        'actual_delivery_end',
        'actual_quantity_delivered',
        'unit_price',
        'total_cost',
        'supplier',
        'delivery_method',
        'notes',
    ];

    protected $casts = [
        'quantity_requested' => 'decimal:2',
        'actual_quantity_delivered' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'requested_time' => 'datetime',
        'scheduled_delivery' => 'datetime',
        'actual_delivery_start' => 'datetime',
        'actual_delivery_end' => 'datetime',
    ];

    // Relationships
    public function portCall()
    {
        return $this->belongsTo(PortCall::class);
    }

    // Helper Methods
    public function calculateCost()
    {
        if (!$this->actual_quantity_delivered || !$this->unit_price) {
            return 0;
        }

        $cost = $this->actual_quantity_delivered * $this->unit_price;
        
        // Add delivery surcharge based on method
        $surcharge = 0;
        if ($this->delivery_method === 'Barge') {
            $surcharge = 500; // Fixed barge fee
        } elseif ($this->delivery_method === 'Truck') {
            $surcharge = 200; // Fixed truck fee
        }

        $totalCost = $cost + $surcharge;
        
        $this->update(['total_cost' => $totalCost]);
        
        return $totalCost;
    }

    public function complete($actualQuantity)
    {
        $this->update([
            'status' => 'completed',
            'actual_delivery_end' => now(),
            'actual_quantity_delivered' => $actualQuantity,
        ]);
        
        $this->calculateCost();
        
        // Deduct from inventory
        $inventory = FuelInventory::where('fuel_type', $this->fuel_type)->first();
        if ($inventory) {
            $inventory->decrement('current_stock', $actualQuantity);
            $inventory->update(['last_restocked_at' => now()]);
        }
    }

    public function approve()
    {
        // Check inventory availability
        $inventory = FuelInventory::where('fuel_type', $this->fuel_type)->first();
        
        if (!$inventory || $inventory->current_stock < $this->quantity_requested) {
            return false; // Insufficient stock
        }

        $this->update(['status' => 'approved']);
        return true;
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', ['requested', 'approved', 'scheduled']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'in_progress');
    }
}
