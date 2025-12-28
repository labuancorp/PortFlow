<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnchorageZone;

class AnchorageSeeder extends Seeder
{
    public function run(): void
    {
        // Zone A: General Anchorage (Near Port)
        // A rough rectangle offshore
        AnchorageZone::create([
            'name' => 'Anchorage A (General)',
            'max_capacity' => 6,
            'min_depth' => 15.0,
            'status' => 'active',
            'boundary_coordinates' => [
                ['lat' => 5.2720, 'lng' => 115.2380],
                ['lat' => 5.2760, 'lng' => 115.2380],
                ['lat' => 5.2760, 'lng' => 115.2405],
                ['lat' => 5.2720, 'lng' => 115.2405],
            ]
        ]);

        // Zone B: Tanker Anchorage (Deeper)
        AnchorageZone::create([
            'name' => 'Anchorage B (Tanker)',
            'max_capacity' => 4,
            'min_depth' => 20.0,
            'status' => 'active',
            'boundary_coordinates' => [
                ['lat' => 5.2650, 'lng' => 115.2350],
                ['lat' => 5.2700, 'lng' => 115.2350],
                ['lat' => 5.2700, 'lng' => 115.2400],
                ['lat' => 5.2650, 'lng' => 115.2400],
            ]
        ]);

        // Zone C: Quarantine Anchorage
        AnchorageZone::create([
            'name' => 'Anchorage C (Quarantine)',
            'max_capacity' => 2,
            'min_depth' => 18.0,
            'status' => 'active',
            'boundary_coordinates' => [
                ['lat' => 5.2780, 'lng' => 115.2300],
                ['lat' => 5.2820, 'lng' => 115.2300],
                ['lat' => 5.2820, 'lng' => 115.2340],
                ['lat' => 5.2780, 'lng' => 115.2340],
            ]
        ]);
    }
}
