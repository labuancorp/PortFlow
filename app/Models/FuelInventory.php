<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelInventory extends Model
{
    use HasFactory;

    protected $table = 'fuel_inventory';

    protected $fillable = [
        'fuel_type',
        'current_stock',
        'minimum_threshold',
        'maximum_capacity',
        'unit',
        'current_price_per_unit',
        'storage_location',
        'last_restocked_at',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_threshold' => 'decimal:2',
        'maximum_capacity' => 'decimal:2',
        'current_price_per_unit' => 'decimal:2',
        'last_restocked_at' => 'datetime',
    ];

    // Helper Methods
    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_threshold;
    }

    public function isCriticalStock()
    {
        return $this->current_stock <= ($this->minimum_threshold * 0.5);
    }

    public function getStockPercentage()
    {
        if ($this->maximum_capacity == 0) {
            return 0;
        }
        return ($this->current_stock / $this->maximum_capacity) * 100;
    }

    public function canFulfillOrder($quantity)
    {
        return $this->current_stock >= $quantity;
    }

    public function restock($quantity, $pricePerUnit = null)
    {
        $newStock = min($this->current_stock + $quantity, $this->maximum_capacity);
        
        $data = [
            'current_stock' => $newStock,
            'last_restocked_at' => now(),
        ];

        if ($pricePerUnit) {
            $data['current_price_per_unit'] = $pricePerUnit;
        }

        $this->update($data);

        return $newStock;
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_threshold');
    }

    public function scopeCriticalStock($query)
    {
        return $query->whereRaw('current_stock <= (minimum_threshold * 0.5)');
    }
}
