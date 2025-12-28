<?php

namespace App\Services;

use App\Models\Tank;
use App\Models\ProductType;
use App\Models\ProductCompatibility;
use App\Models\TankReading;
use Illuminate\Support\Facades\DB;
use Exception;

class TankFarmService
{
    /**
     * Check if a product can be introduced into a tank.
     */
    public function canLoadProduct(Tank $tank, int $productId): array
    {
        // 1. If tank is empty/clean, any product is fine (unless tank type restrictions exist)
        if ($tank->current_volume <= 0 || !$tank->current_product_id) {
            // Check if tank is flagged as contaminated or needing cleaning from previous product?
            // For MVP: if volume is 0, we assume it's safe OR we check last product.
            // Let's assume strict: if 0 volume, we verify if cleaning was done if previous product required it.
            // Simplified: If 0 volume, allowed.
            return ['allowed' => true];
        }

        // 2. If tank has same product, allowed.
        if ($tank->current_product_id === $productId) {
            return ['allowed' => true];
        }

        // 3. Different product: Check compatibility
        $compatibility = ProductCompatibility::where(function ($q) use ($tank, $productId) {
            $q->where('product_a_id', $tank->current_product_id)->where('product_b_id', $productId);
        })->orWhere(function ($q) use ($tank, $productId) {
            $q->where('product_a_id', $productId)->where('product_b_id', $tank->current_product_id);
        })->first();

        if ($compatibility && $compatibility->is_compatible) {
            return ['allowed' => true, 'warning' => 'Compatible product, but mixing will occur.'];
        }

        return [
            'allowed' => false, 
            'reason' => 'Incompatible product. Tank contains ' . $tank->product->name . '. Cleaning required.'
        ];
    }

    /**
     * Update tank volume (e.g., from sensor or manual dip).
     */
    public function updateVolume(Tank $tank, float $newVolume, string $source = 'manual', ?float $temp = null)
    {
        if ($newVolume > $tank->capacity_volume) {
            throw new Exception("Volume exceeds tank capacity.");
        }

        $tank->update(['current_volume' => $newVolume]);

        // Log reading
        TankReading::create([
            'tank_id' => $tank->id,
            'reading_volume' => $newVolume,
            'temperature' => $temp,
            'source' => $source,
            'recorded_by' => auth()->id() ?? null
        ]);

        // If volume -> 0, we might want to prompt for cleaning status update, but we leave that to manual workflow.
    }
}
