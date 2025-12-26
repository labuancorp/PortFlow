<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\WarehouseZone;
use App\Models\CargoItem;
use App\Models\CargoManifest;
use App\Models\Vessel;
use App\Models\Organization;
use Illuminate\Support\Str;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Warehouses
        $mainYard = Warehouse::create([
            'name' => 'Main Terminal Yard',
            'type' => 'open_yard',
            'total_capacity_m3' => 50000,
        ]);

        $hazmatShed = Warehouse::create([
            'name' => 'DG Storage Facility',
            'type' => 'covered',
            'total_capacity_m3' => 5000,
        ]);

        // 2. Create Zones
        // Main Yard Zones (A, B, C)
        $zoneA = WarehouseZone::create([
            'warehouse_id' => $mainYard->id,
            'name' => 'Block A (General)',
            'code' => 'YARD-A',
            'capacity_limit_m3' => 15000,
            'is_dg_allowed' => false,
        ]);
        
        $zoneB = WarehouseZone::create([
            'warehouse_id' => $mainYard->id,
            'name' => 'Block B (Heavy)',
            'code' => 'YARD-B',
            'capacity_limit_m3' => 20000,
            'is_dg_allowed' => false,
        ]);

        // Hazmat Zones
        $zoneDG1 = WarehouseZone::create([
            'warehouse_id' => $hazmatShed->id,
            'name' => 'Flammables',
            'code' => 'DG-FLM',
            'capacity_limit_m3' => 2500,
            'is_dg_allowed' => true,
        ]);
        
         $zoneDG2 = WarehouseZone::create([
            'warehouse_id' => $hazmatShed->id,
            'name' => 'Explosives Bunker',
            'code' => 'DG-EXP',
            'capacity_limit_m3' => 1000,
            'is_dg_allowed' => true,
        ]);

        // 3. Populate with items to show density
        $this->fillZone($zoneA, 40); // 40% full (Green)
        $this->fillZone($zoneDG1, 85); // 85% full (Red)
        $this->fillZone($zoneB, 65); // 65% full (Yellow)

        // 4. Create Aging Items
        $this->createAgingItems($zoneB);
    }

    private function fillZone($zone, $percentage)
    {
        $targetVolume = ($zone->capacity_limit_m3 * $percentage) / 100;
        $currentVolume = 0;

        $vessel = Vessel::first();
        $agent = Organization::where('type', 'agent')->first();
        if(!$vessel) return;

        $manifest = CargoManifest::create([
            'vessel_id' => $vessel->id,
            'agent_id' => $agent->id,
            'reference_no' => 'MNF-WHS-' . Str::random(5),
            'type' => 'inbound',
            'status' => 'discharged',
            'eta_etd' => now(),
        ]);

        while ($currentVolume < $targetVolume) {
            $vol = rand(10, 500);
            CargoItem::create([
                'cargo_manifest_id' => $manifest->id,
                'warehouse_zone_id' => $zone->id,
                'tracking_number' => 'STK-' . Str::upper(Str::random(6)),
                'description' => 'Stockpile Item',
                'weight_kg' => 1000,
                'volume_m3' => $vol,
                'status' => 'at_wharf',
                'created_at' => now(), // Fresh item
            ]);
            $currentVolume += $vol;
        }
    }

    private function createAgingItems($zone)
    {
        $vessel = Vessel::first();
        $agent = Organization::where('type', 'agent')->first();
        
        $manifest = CargoManifest::create([
            'vessel_id' => $vessel->id,
            'agent_id' => $agent->id,
            'reference_no' => 'MNF-OLD-' . Str::random(5),
            'type' => 'inbound',
            'status' => 'discharged',
            'eta_etd' => now()->subDays(100),
        ]);

        for($i=0; $i<5; $i++) {
             CargoItem::create([
                'cargo_manifest_id' => $manifest->id,
                'warehouse_zone_id' => $zone->id,
                'tracking_number' => 'OLD-' . Str::upper(Str::random(6)),
                'description' => 'Unclaimed Machinery Part #' . $i,
                'weight_kg' => 500,
                'volume_m3' => 10,
                'status' => 'at_wharf',
                'created_at' => now()->subDays(rand(95, 120)), // 95-120 days old
            ]);
        }
    }
}
