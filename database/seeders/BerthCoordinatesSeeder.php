<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berth;
use Illuminate\Support\Facades\DB;

class BerthCoordinatesSeeder extends Seeder
{
    public function run(): void
    {
        // Precise coordinates for Labuan Liberty Wharf (Jetties)
        $berthCoordinates = [
            // Adjusted to be ON the jetty/water
            'Main Wharf 1' => ['lat' => 5.2758, 'lng' => 115.2410, 'loa' => 80, 'draft' => 5], // SMALL BERTH
            'Main Wharf 2' => ['lat' => 5.2763, 'lng' => 115.2413, 'loa' => 200, 'draft' => 12], // LARGE BERTH
            'Main Wharf 3' => ['lat' => 5.2768, 'lng' => 115.2416, 'loa' => 200, 'draft' => 12],
            'Alpha Jetty' => ['lat' => 5.2773, 'lng' => 115.2419, 'loa' => 200, 'draft' => 12],
            'Bravo Jetty' => ['lat' => 5.2778, 'lng' => 115.2422, 'loa' => 200, 'draft' => 12],
        ];
        
        $berths = Berth::all();
        
        foreach ($berths as $berth) {
            // Fuzzy match name
            $coords = null;
            foreach ($berthCoordinates as $name => $data) {
                if (stripos($berth->name, $name) !== false || stripos($name, $berth->name) !== false) {
                     $coords = $data;
                     break;
                }
            }
            
            // Default if not matched
            if (!$coords) {
                continue;
            }
            
            $berth->update([
                'latitude' => $coords['lat'],
                'longitude' => $coords['lng'],
                'max_loa' => $coords['loa'],
                'max_draft' => $coords['draft']
            ]);
            
            $this->command->info("Updated {$berth->name}: {$coords['lat']}, {$coords['lng']} (LOA: {$coords['loa']})");
        }
    }
}
