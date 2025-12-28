<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WarehouseZone;

class WarehouseGISSeeder extends Seeder
{
    public function run(): void
    {
        // Update existing zones with coordinates
        $zones = WarehouseZone::all();
        
        if ($zones->count() === 0) {
            // Create dummy zones if none exist
            WarehouseZone::create(['name' => 'Zone A', 'capacity_limit_m3' => 1000]);
            WarehouseZone::create(['name' => 'Zone B', 'capacity_limit_m3' => 1000]);
            $zones = WarehouseZone::all();
        }

        $baseLat = 5.2790;
        $baseLng = 115.2410;
        $offset = 0;

        foreach ($zones as $zone) {
            // Create a small rectangle for each
            $lat1 = $baseLat;
            $lng1 = $baseLng + ($offset * 0.001);
            $lat2 = $baseLat + 0.0005;
            $lng2 = $lng1 + 0.0008;

            $zone->update([
                'map_coordinates' => [
                    ['lat' => $lat1, 'lng' => $lng1],
                    ['lat' => $lat2, 'lng' => $lng1],
                    ['lat' => $lat2, 'lng' => $lng2],
                    ['lat' => $lat1, 'lng' => $lng2],
                ],
                'total_area_sqm' => 500
            ]);
            
            $offset++;
        }
    }
}
