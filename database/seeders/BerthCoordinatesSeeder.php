<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berth;
use Illuminate\Support\Facades\DB;

class BerthCoordinatesSeeder extends Seeder
{
    public function run(): void
    {
        // Sample berth coordinates for Labuan Port (Approximate)
        $berthCoordinates = [
            'Berth 1' => ['lat' => 5.2750, 'lng' => 115.2400],
            'Berth 2' => ['lat' => 5.2755, 'lng' => 115.2405],
            'Berth 3' => ['lat' => 5.2760, 'lng' => 115.2410],
            'Berth 4' => ['lat' => 5.2765, 'lng' => 115.2415],
            'Berth 5' => ['lat' => 5.2770, 'lng' => 115.2420],
            'Liqud Bulk Terminal' => ['lat' => 5.2780, 'lng' => 115.2430], // Example name
            'LBT 1' => ['lat' => 5.2780, 'lng' => 115.2430], 
            'LBT 2' => ['lat' => 5.2785, 'lng' => 115.2435],
        ];
        
        // Also update generic names if they exist like "Alpha", "Beta" depending on user data
        // For now, I'll fetch all berths and assign coordinates incrementally if names don't match
        
        $berths = Berth::all();
        $baseLat = 5.2750;
        $baseLng = 115.2400;
        
        foreach ($berths as $index => $berth) {
            $coords = $berthCoordinates[$berth->name] ?? null;
            
            if (!$coords) {
                // Assign incremental coordinates for demo
                $coords = [
                    'lat' => $baseLat + ($index * 0.0005),
                    'lng' => $baseLng + ($index * 0.0005)
                ];
            }
            
            $berth->update([
                'latitude' => $coords['lat'],
                'longitude' => $coords['lng']
            ]);
            
            $this->command->info("Updated {$berth->name} with coordinates: {$coords['lat']}, {$coords['lng']}");
        }
    }
}
