<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vessel;
use App\Models\VesselPosition;

class SimulateVesselTraffic extends Command
{
    protected $signature = 'gis:simulate-traffic {--continuous}';
    protected $description = 'Simulate AIS vessel traffic updates';

    public function handle()
    {
        $this->info("Starting Vessel Traffic Simulation...");
        
        $vessels = Vessel::all();
        
        if ($vessels->count() === 0) {
            $this->error("No vessels found!");
            return;
        }

        $continuous = $this->option('continuous');

        do {
            foreach ($vessels as $vessel) {
                $this->updateVesselPosition($vessel);
            }
            
            $this->info("Updated positions for " . $vessels->count() . " vessels.");
            
            if ($continuous) {
                sleep(2); // Wait 2 seconds
            }
            
        } while ($continuous);
    }

    private function updateVesselPosition($vessel)
    {
        // Get last position
        $lastPos = VesselPosition::where('vessel_id', $vessel->id)
                    ->orderBy('recorded_at', 'desc')
                    ->first();

        if (!$lastPos) {
            // Start at a random anchorage spot
            $lat = 5.2700 + (rand(-10, 10) / 1000); // 5.269 - 5.271
            $lng = 115.2350 + (rand(-10, 10) / 1000);
            $heading = rand(0, 360);
            $speed = 0;
            $status = 'Anchored';
        } else {
            // Move randomly but mostly towards Port Center (5.2768, 115.2415)
            $targetLat = 5.2768;
            $targetLng = 115.2415;
            
            $currentLat = $lastPos->latitude;
            $currentLng = $lastPos->longitude;
            
            // Simple logic: If far, move towards. If close, stop.
            $dist = sqrt(pow($targetLat - $currentLat, 2) + pow($targetLng - $currentLng, 2));
            
            if ($dist < 0.0005) {
                // At berth / stop
                $lat = $currentLat;
                $lng = $currentLng;
                $speed = 0;
                $status = 'Moored';
                $heading = $lastPos->heading;
            } else {
                // Move towards target
                $step = 0.0001; // ~10 meters
                $angle = atan2($targetLng - $currentLng, $targetLat - $currentLat);
                
                $lat = $currentLat + ($step * cos($angle));
                $lng = $currentLng + ($step * sin($angle));
                $speed = rand(5, 12);
                $status = 'Underway';
                $heading = rad2deg($angle);
                if ($heading < 0) $heading += 360;
            }
        }

        VesselPosition::create([
            'vessel_id' => $vessel->id,
            'latitude' => $lat,
            'longitude' => $lng,
            'speed' => $speed,
            'heading' => $heading,
            'status' => $status,
            'recorded_at' => now()
        ]);
        
        // Keep DB small - delete old positions (> 1 hour)
        VesselPosition::where('vessel_id', $vessel->id)
            ->where('recorded_at', '<', now()->subHour())
            ->delete();
    }
}
