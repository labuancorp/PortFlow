<?php

namespace App\Livewire\Map;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Berth;

class PortMap extends Component
{
    // Hardcoded Coordinates for Demo (Phase 1)
    // In Phase 2, these would be columns in the `berths` table: lat/lng
    const BERTH_COORDS = [
        'MW1' => [5.262, 115.242],
        'MW2' => [5.263, 115.243],
        'MW3' => [5.264, 115.244],
        'AJ1' => [5.261, 115.241],
    ];

    public function render()
    {
        $activeVessels = PortCall::with(['vessel', 'berth'])
            ->whereIn('status', ['alongside', 'anchored', 'approaching'])
            ->get()
            ->map(function ($call) {
                // Determine Coordinates based on status
                $coords = [5.250, 115.230]; // Default: Approaching (Sea)

                if ($call->status === 'alongside' && $call->berth) {
                    $code = $call->berth->code; // e.g., MW1
                    $coords = self::BERTH_COORDS[$code] ?? [5.262, 115.242];
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

        return view('livewire.map.port-map', [
            'vessels' => $activeVessels,
            'berths' => Berth::all()
        ]);
    }
}
