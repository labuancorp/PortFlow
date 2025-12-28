<?php

namespace App\Livewire\GIS;

use Livewire\Component;
use App\Models\Berth;
use App\Models\PortCall;
use Illuminate\Support\Facades\DB;

class PortMapView extends Component
{
    public $selectedBerth = null;
    
    public function getBerthsData()
    {
        $berths = Berth::with(['currentPortCall.vessel'])
            ->get()
            ->map(function($berth) {
                return [
                    'id' => $berth->id,
                    'name' => $berth->name,
                    'lat' => $berth->latitude,
                    'lng' => $berth->longitude,
                    'status' => strtolower($berth->status), // Ensure lowercase for JS mapping
                    'length' => $berth->max_loa, 
                    'draft' => $berth->max_draft,
                    'vessel' => $berth->currentPortCall ? [
                        'name' => $berth->currentPortCall->vessel->name,
                        'imo' => $berth->currentPortCall->vessel->imo_number
                    ] : null
                ];
            });
        
        return $berths;
    }
    
    public function selectBerth($berthId)
    {
        $this->selectedBerth = Berth::with(['currentPortCall.vessel'])->find($berthId);
        
        if ($this->selectedBerth) {
            $this->dispatch('berth-selected', [
                'lat' => $this->selectedBerth->latitude,
                'lng' => $this->selectedBerth->longitude
            ]);
        }
    }
    
    public function getWaitingVessels()
    {
        return \App\Models\PortCall::with('vessel')
            ->whereIn('status', ['requested', 'anchored'])
            ->whereNull('assigned_berth_id')
            ->get();
    }
    
    public function assignToAnchorage($vesselId, $zoneId)
    {
        $portCall = \App\Models\PortCall::where('vessel_id', $vesselId)->whereIn('status', ['requested', 'anchored', 'alongside'])->first();
        $zone = \App\Models\AnchorageZone::find($zoneId);
        
        if (!$portCall || !$zone) {
            $this->dispatch('alert', ['type' => 'error', 'message' => "Invalid vessel or zone."]);
            return;
        }

        // Capacity Check
        if ($zone->portCalls()->count() >= $zone->max_capacity) {
            $this->dispatch('alert', ['type' => 'error', 'message' => "Anchorage zone is full!"]);
            return;
        }
        
        \DB::transaction(function() use ($portCall, $zone) {
            // If currently at berth, free the berth
            if ($portCall->assigned_berth_id) {
                $portCall->berth->update(['status' => 'available']);
            }
            
            $portCall->update([
                'assigned_berth_id' => null,
                'anchorage_zone_id' => $zone->id,
                'status' => 'anchored',
                'anchored_at' => now()
            ]);
        });
        
        $this->dispatch('alert', ['type' => 'success', 'message' => "Vessel moved to anchorage."]);
        $this->dispatch('refresh-map');
    }

    public function assignVessel($vesselId, $berthId)
    {
        $portCall = \App\Models\PortCall::where('vessel_id', $vesselId)->whereIn('status', ['requested', 'anchored'])->first();
        $berth = Berth::find($berthId);
        
        if (!$portCall || !$berth) return;
        
        // Validation
        if ($berth->status !== 'available') {
            $this->dispatch('alert', ['type' => 'error', 'message' => "Berth is already occupied!"]);
            return;
        }
        
        if ($portCall->vessel->loa_meters > $berth->max_loa) {
            $this->dispatch('alert', ['type' => 'error', 'message' => "Vessel exceeds berth max LOA!"]);
            return;
        }
        
        if ($portCall->vessel->draft_meters > $berth->max_draft) {
             $this->dispatch('alert', ['type' => 'error', 'message' => "Vessel draft too deep for this berth!"]);
             return;
        }
        
        // Assign
        $portCall->update([
            'assigned_berth_id' => $berth->id,
            'status' => 'alongside',
            'berthed_at' => now()
        ]);
        
        $berth->update(['status' => 'occupied']);
        
        $this->dispatch('alert', ['type' => 'success', 'message' => "Vessel assigned successfully!"]);
        // $this->dispatch('refresh-map'); // If we implement map refresh logic
    }

    public function updateMapPositions()
    {
        $vesselPositions = \App\Models\VesselPosition::query()
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                      ->from('vessel_positions')
                      ->groupBy('vessel_id');
            })
            ->with('vessel')
            ->get()
            ->map(function($pos) {
                return [
                    'vessel_id' => $pos->vessel_id,
                    'name' => $pos->vessel->name,
                    'lat' => $pos->latitude,
                    'lng' => $pos->longitude,
                    'speed' => $pos->speed,
                    'heading' => $pos->heading,
                    'status' => $pos->status,
                    'timestamp' => $pos->recorded_at->toIso8601String()
                ];
            });

        $this->dispatch('update-vessel-positions', $vesselPositions);
    }

    public function render()
    {
        $anchorages = \App\Models\AnchorageZone::all()->map(function($zone) {
            return [
                'id' => $zone->id,
                'name' => $zone->name,
                'capacity' => $zone->max_capacity,
                'current' => $zone->portCalls()->count(),
                'coordinates' => $zone->boundary_coordinates
            ];
        });

        // Fetch latest positions
        $vesselPositions = \App\Models\VesselPosition::query()
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                      ->from('vessel_positions')
                      ->groupBy('vessel_id');
            })
            ->with('vessel')
            ->get()
            ->map(function($pos) {
                return [
                    'vessel_id' => $pos->vessel_id,
                    'name' => $pos->vessel->name,
                    'lat' => $pos->latitude,
                    'lng' => $pos->longitude,
                    'speed' => $pos->speed,
                    'heading' => $pos->heading,
                    'status' => $pos->status,
                    'timestamp' => $pos->recorded_at->toIso8601String()
                ];
            });

        // Warehouses
        $warehouses = \App\Models\WarehouseZone::whereNotNull('map_coordinates')->get()->map(function($zone) {
             return [
                 'id' => $zone->id,
                 'name' => $zone->name,
                 'coordinates' => $zone->map_coordinates
             ];
        });

        // Demo Cargo Flows
        $flows = [];
        $occupiedBerths = collect($this->getBerthsData())->where('status', 'occupied');
        if ($warehouses->isNotEmpty()) {
            foreach ($occupiedBerths as $berth) {
                 $targetWarehouse = $warehouses->random();
                 $flows[] = [
                     'from' => ['lat' => $berth['lat'], 'lng' => $berth['lng']],
                     'to' => $targetWarehouse['coordinates'][0], // target corner
                     'type' => 'import'
                 ];
            }
        }

        return view('livewire.gis.port-map-view', [
            'berthsData' => $this->getBerthsData(),
            'waitingVessels' => $this->getWaitingVessels(),
            'anchorageData' => $anchorages,
            'vesselPositions' => $vesselPositions,
            'warehouseData' => $warehouses,
            'cargoFlows' => $flows
        ])->layout('components.layouts.app'); 
    }
}
