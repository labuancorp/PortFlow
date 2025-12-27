<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GateEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚧 Seeding Gate Entries (Phase 1)...');

        \App\Models\GateEntry::create([
            'driver_name' => 'Ali Bin Abu',
            'driver_ic' => '800101-12-1234',
            'vehicle_plate' => 'LAB 1234',
            'cargo_description' => 'Construction Materials (Cement)',
            'status' => 'pending',
            'created_at' => now()->subMinutes(10),
        ]);

        \App\Models\GateEntry::create([
            'driver_name' => 'Chong Wei',
            'driver_ic' => '900202-10-5678',
            'vehicle_plate' => 'SAB 5678',
            'cargo_description' => 'Industrial Solvents (Class 3 DG)',
            'has_dangerous_goods' => true,
            'status' => 'pending',
            'created_at' => now()->subMinutes(30),
        ]);

        \App\Models\GateEntry::create([
            'driver_name' => 'Raju A/L Muthu',
            'driver_ic' => '850505-08-9999',
            'vehicle_plate' => 'KV 9999',
            'cargo_description' => 'Drill Bits Replacement',
            'status' => 'checked_in',
            'scanned_at' => now()->subHours(1),
            'gate_in_at' => now()->subHours(1),
            'created_at' => now()->subHours(2),
        ]);
    }
}
