<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MheEquipment;

class MheSeeder extends Seeder
{
    public function run(): void
    {
        $equipment = [
            ['name' => 'Forklift 3T-01', 'code' => 'FL-01', 'type' => 'Forklift', 'model' => 'Toyota 3T', 'hrs' => 1200, 'loc' => 'Warehouse A'],
            ['name' => 'Forklift 3T-02', 'code' => 'FL-02', 'type' => 'Forklift', 'model' => 'Toyota 3T', 'hrs' => 3400, 'loc' => 'Warehouse B'],
            ['name' => 'Forklift 5T-01', 'code' => 'FL-03', 'type' => 'Forklift', 'model' => 'Hyster 5T', 'hrs' => 500, 'loc' => 'Yard'],
            ['name' => 'Mobile Crane 45T', 'code' => 'MC-01', 'type' => 'Crane', 'model' => 'Liebherr LTM', 'hrs' => 8000, 'loc' => 'Wharf 1'],
            ['name' => 'Mobile Crane 20T', 'code' => 'MC-02', 'type' => 'Crane', 'model' => 'Kato 20T', 'hrs' => 4500, 'loc' => 'Wharf 2'],
            ['name' => 'Reach Stacker 01', 'code' => 'RS-01', 'type' => 'Reach Stacker', 'model' => 'Konecranes', 'hrs' => 2100, 'loc' => 'Container Yard'],
            ['name' => 'Prime Mover 01', 'code' => 'PM-01', 'type' => 'Prime Mover', 'model' => 'Volvo FH16', 'hrs' => 15000, 'loc' => 'Gate'],
            ['name' => 'Prime Mover 02', 'code' => 'PM-02', 'type' => 'Prime Mover', 'model' => 'Scania R500', 'hrs' => 12000, 'loc' => 'Gate'],
        ];

        foreach ($equipment as $e) {
            MheEquipment::create([
                'name' => $e['name'],
                'asset_code' => $e['code'],
                'type' => $e['type'],
                'model' => $e['model'],
                'manufacturer' => 'Generic',
                'year' => rand(2018, 2024),
                'status' => rand(0, 5) === 0 ? 'maintenance' : 'available', // 1 in 6 chance of maintenance
                'current_hour_meter' => $e['hrs'],
                'next_pm_due_hours' => $e['hrs'] + 250, // PM due in 250 hours
                'next_pm_due_date' => now()->addMonths(3),
                'location' => $e['loc']
            ]);
        }
    }
}
