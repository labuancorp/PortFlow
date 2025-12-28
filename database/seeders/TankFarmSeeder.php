<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductType;
use App\Models\ProductCompatibility;
use App\Models\Tank;

class TankFarmSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Products
        $obm = ProductType::create([
            'name' => 'Oil Based Mud', 'code' => 'OBM', 'color_code' => '#10B981', // Emerald
            'specific_gravity' => 1.25, 'hazard_class' => 'Environmentally Hazardous'
        ]);
        $wbm = ProductType::create([
            'name' => 'Water Based Mud', 'code' => 'WBM', 'color_code' => '#3B82F6', // Blue
            'specific_gravity' => 1.10, 'hazard_class' => 'Non-Hazardous'
        ]);
        $brine = ProductType::create([
            'name' => 'Calcium Chloride Brine', 'code' => 'Brine', 'color_code' => '#F3F4F6', // Gray/White
            'specific_gravity' => 1.30, 'hazard_class' => 'Irritant'
        ]);
        $baseOil = ProductType::create([
            'name' => 'Base Oil', 'code' => 'BO', 'color_code' => '#F59E0B', // Amber
            'specific_gravity' => 0.85, 'hazard_class' => 'Flammable'
        ]);

        // 2. Compatibility Rules
        // OBM <-> WBM (Incompatible)
        ProductCompatibility::create(['product_a_id' => $obm->id, 'product_b_id' => $wbm->id, 'is_compatible' => false, 'notes' => 'Requires full flush and detergent wash.']);
        // OBM <-> Base Oil (Compatible)
        ProductCompatibility::create(['product_a_id' => $obm->id, 'product_b_id' => $baseOil->id, 'is_compatible' => true, 'notes' => 'Compatible base fluid.']);
        // Brine <-> WBM (Compatible depending on chemistry, assuming true for demo)
        ProductCompatibility::create(['product_a_id' => $brine->id, 'product_b_id' => $wbm->id, 'is_compatible' => true]);

        // 3. Create Tanks
        // Zone A (Mud Plant)
        $tanks = [
            ['name' => 'T-101', 'cap' => 1000, 'prod' => $obm->id, 'vol' => 850, 'lat' => 5.275, 'lng' => 115.245],
            ['name' => 'T-102', 'cap' => 1000, 'prod' => $obm->id, 'vol' => 400, 'lat' => 5.2751, 'lng' => 115.2451],
            ['name' => 'T-103', 'cap' => 1000, 'prod' => $wbm->id, 'vol' => 900, 'lat' => 5.2752, 'lng' => 115.2452],
            ['name' => 'T-104', 'cap' => 500, 'prod' => null, 'vol' => 0, 'lat' => 5.2753, 'lng' => 115.2453], // Empty
            ['name' => 'T-105', 'cap' => 500, 'prod' => $baseOil->id, 'vol' => 350, 'lat' => 5.2754, 'lng' => 115.2454],
        ];

        foreach ($tanks as $t) {
            Tank::create([
                'name' => $t['name'],
                'capacity_volume' => $t['cap'],
                'current_volume' => $t['vol'],
                'current_product_id' => $t['prod'],
                'status' => 'active',
                'gis_coordinates' => ['lat' => $t['lat'], 'lng' => $t['lng']]
            ]);
        }
    }
}
