<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name' => 'Safety Helmet (White)', 'sku' => 'PPE-HLM-W', 'category' => 'PPE', 'current_stock' => 150, 'min_threshold' => 20, 'unit' => 'pcs', 'location' => 'Store B-1'],
            ['name' => 'High-Vis Vest (L)', 'sku' => 'PPE-VST-L', 'category' => 'PPE', 'current_stock' => 12, 'min_threshold' => 15, 'unit' => 'pcs', 'location' => 'Store B-2'], // Low Stock
            ['name' => 'Safety Gloves (Leather)', 'sku' => 'PPE-GLV-LTH', 'category' => 'PPE', 'current_stock' => 300, 'min_threshold' => 50, 'unit' => 'pairs', 'location' => 'Store B-3'],
            ['name' => 'Diesel Fuel (Euro 5)', 'sku' => 'FUEL-DSL-E5', 'category' => 'Fuel', 'current_stock' => 4500, 'min_threshold' => 5000, 'unit' => 'liters', 'location' => 'Tank Farm 1'], // Low Stock
            ['name' => 'Hydraulic Fluid ISO 46', 'sku' => 'LUB-HYD-46', 'category' => 'Consumables', 'current_stock' => 200, 'min_threshold' => 50, 'unit' => 'liters', 'location' => 'Hazmat Shed'],
            ['name' => 'Crane Wire Rope (20mm)', 'sku' => 'SPR-CRN-WR20', 'category' => 'Spares', 'current_stock' => 2, 'min_threshold' => 2, 'unit' => 'reels', 'location' => 'Yard Zone C'],
        ];

        foreach ($items as $item) {
            InventoryItem::updateOrCreate(['sku' => $item['sku']], $item);
        }
    }
}
