<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CargoManifest;
use App\Models\CargoItem;
use App\Models\Vessel;
use App\Models\Organization;
use Illuminate\Support\Str;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $vessels = Vessel::all();
        $agents = Organization::where('type', 'agent')->get();

        if ($vessels->count() === 0 || $agents->count() === 0) {
            $this->command->info('No vessels or agents found. Skipping Cargo Seeder.');
            return;
        }

        // 1. Inbound Manifest with Dangerous Goods (Chemicals)
        $mnf1 = CargoManifest::create([
            'vessel_id' => $vessels->random()->id,
            'agent_id' => $agents->random()->id,
            'reference_no' => 'MNF-IN-2025-001',
            'type' => 'inbound',
            'status' => 'submitted',
            'eta_etd' => now()->addDays(2),
        ]);

        CargoItem::create([
            'cargo_manifest_id' => $mnf1->id,
            'tracking_number' => 'TRK-CHEM-001',
            'description' => 'Industrial Solvent (Flammable)',
            'weight_kg' => 500.00,
            'volume_m3' => 1.2,
            'dg_class' => '3', // Flammable Liquid
            'status' => 'pending',
            'current_location' => 'Vessel Hold 1'
        ]);

        CargoItem::create([
            'cargo_manifest_id' => $mnf1->id,
            'tracking_number' => 'TRK-PIPE-102',
            'description' => 'Steel Casing Pipes (Set of 10)',
            'weight_kg' => 2500.00,
            'volume_m3' => 5.5,
            'dg_class' => null,
            'status' => 'pending',
            'current_location' => 'Vessel Deck'
        ]);

        // 2. Outbound Manifest (General Cargo)
        $mnf2 = CargoManifest::create([
            'vessel_id' => $vessels->random()->id,
            'agent_id' => $agents->random()->id,
            'reference_no' => 'MNF-OUT-2025-045',
            'type' => 'outbound',
            'status' => 'approved',
            'eta_etd' => now()->addHours(12),
        ]);

        for ($i = 1; $i <= 5; $i++) {
            CargoItem::create([
                'cargo_manifest_id' => $mnf2->id,
                'tracking_number' => 'TRK-GEN-' . Str::upper(Str::random(4)),
                'description' => 'General Provisions Box #' . $i,
                'weight_kg' => rand(20, 100),
                'volume_m3' => rand(1, 5) / 10,
                'dg_class' => null,
                'status' => 'gated_in',
                'current_location' => 'Warehouse A'
            ]);
        }

        // 3. Draft Manifest
        CargoManifest::create([
            'vessel_id' => $vessels->random()->id,
            'agent_id' => $agents->random()->id,
            'reference_no' => 'MNF-DRAFT-099',
            'type' => 'outbound',
            'status' => 'draft',
            'eta_etd' => now()->addDays(5),
        ]); // No items yet

        // 4. Completed Inbound
        $mnf4 = CargoManifest::create([
            'vessel_id' => $vessels->random()->id,
            'agent_id' => $agents->random()->id,
            'reference_no' => 'MNF-IN-2025-002',
            'type' => 'inbound',
            'status' => 'loaded', // Actually discharged for inbound
            'eta_etd' => now()->subDays(1),
        ]);

        CargoItem::create([
            'cargo_manifest_id' => $mnf4->id,
            'tracking_number' => 'TRK-EXPL-999',
            'description' => 'Seismic Charges (Explosives)',
            'weight_kg' => 120.00,
            'volume_m3' => 0.5,
            'dg_class' => '1', // Explosive
            'status' => 'discharged',
            'current_location' => 'Secure Bunker'
        ]);
    }
}
