<?php

namespace App\Livewire\Map;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Berth;

class PortMap extends Component
{
    public function render()
    {
        $activeVessels = PortCall::with(['vessel', 'berth'])
            ->whereIn('status', ['alongside', 'anchored', 'approaching'])
            ->get()
            ->map(function ($call) {
                // Determine Coordinates based on status
                $coords = [5.250, 115.230]; // Default: Approaching (Sea)

                if ($call->status === 'alongside' && $call->berth) {
                     // Use Berth coordinates if available, otherwise fallback
                    if ($call->berth->latitude && $call->berth->longitude) {
                        $coords = [$call->berth->latitude, $call->berth->longitude];
                    } else {
                        // Fallback logic if coordinates missing
                        $coords = [5.262, 115.242]; 
                    }
                } elseif ($call->status === 'anchored') {
                    // Randomize slightly in Anchorage Area
                    $coords = [
                        5.255 + (mt_rand(-20, 20) / 10000), 
                        115.235 + (mt_rand(-20, 20) / 10000)
                    ];
                } elseif ($call->status === 'approaching') {
                    // Further out
                    $coords = [
                        5.245 + (mt_rand(-20, 20) / 10000), 
                        115.225 + (mt_rand(-20, 20) / 10000)
                    ];
                }

                return [
                    'id' => $call->id,
                    'name' => $call->vessel->name,
                    'type' => $call->vessel->vessel_type,
                    'status' => $call->status,
                    'lat' => $coords[0],
                    'lng' => $coords[1],
                    'berth' => $call->berth->name ?? 'None'
                ];
            });

        // Fetch berths with coordinates and colors
        $berths = Berth::all()->map(function ($berth) {
            return [
                'id' => $berth->id,
                'name' => $berth->name,
                'code' => $berth->code,
                'latitude' => $berth->latitude,
                'longitude' => $berth->longitude,
                'color' => $berth->color,
                'status' => $berth->status
            ];
        });

        return view('livewire.map.port-map', [
            'vessels' => $activeVessels,
            'berths' => $berths
        ]);
    }
}
