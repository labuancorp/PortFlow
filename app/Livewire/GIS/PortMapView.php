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
                    'status' => $berth->status,
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
    
    public function render()
    {
        return view('livewire.gis.port-map-view', [
            'berthsData' => $this->getBerthsData()
        ])->layout('components.layouts.app'); // Correct layout path
    }
}
