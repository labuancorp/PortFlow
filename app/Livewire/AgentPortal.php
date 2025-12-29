<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Organization;
use App\Models\Vessel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AgentPortal extends Component
{
    public $agentId;
    public $activeTab = 'live'; // live, scheduled, history, commercial
    
    // Drill Down State
    public $activeSection = null; // 'marine', 'yard', 'assets'
    
    // Modal State
    public $showModal = false;
    public $showVesselModal = false;
    
    // Request Form Fields - Berthing
    public $vessel_id;
    public $eta;
    public $etd;
    
    // Vessel Registration Fields
    public $new_vessel_name = '';
    public $new_vessel_type = 'Offshore Support Vessel';
    public $new_vessel_imo = '';
    public $new_vessel_loa = '';
    public $new_vessel_draft = '';

    // AI Berth Suggestions
    public $showSuggestions = false;
    public $berthSuggestions = [];
    public $selectedSuggestedBerth = null;

    protected $rules = [
        'vessel_id' => 'required',
        'eta' => 'required|date|after:now',
        'etd' => 'required|date|after:eta',
    ];

    // Service Request Fields
    public $showServiceModal = false;
    public $selectedPortCall = null;
    public $serviceType = 'water';
    public $serviceQuantity = 0;
    public $serviceUnit = 'MT';
    public $serviceDate;

    public function openServiceModal($portCallId)
    {
        $this->selectedPortCall = PortCall::find($portCallId);
        $this->serviceType = 'water';
        $this->serviceQuantity = 100;
        $this->serviceUnit = 'MT';
        $this->serviceDate = now()->format('Y-m-d\TH:i');
        $this->showServiceModal = true;
    }

    public function updatedServiceType()
    {
        switch ($this->serviceType) {
            case 'fuel': 
                $this->serviceUnit = 'Liters'; 
                $this->serviceQuantity = 5000;
                break;
            case 'waste': 
                $this->serviceUnit = 'Kg'; 
                $this->serviceQuantity = 500;
                break;
            case 'crane': 
                $this->serviceUnit = 'Hours'; 
                $this->serviceQuantity = 4;
                break;
            default: 
                $this->serviceUnit = 'MT';
                $this->serviceQuantity = 100; // Water
        }
    }

    public function saveServiceRequest()
    {
        $this->validate([
            'serviceType' => 'required',
            'serviceQuantity' => 'required|numeric|min:1',
            'serviceDate' => 'required|date'
        ]);

        \App\Models\ServiceRequest::create([
            'port_call_id' => $this->selectedPortCall->id,
            'service_type' => $this->serviceType,
            'quantity' => $this->serviceQuantity,
            'unit' => $this->serviceUnit,
            'status' => 'pending',
            'requested_at' => $this->serviceDate
        ]);

        $this->showServiceModal = false;
        $this->dispatch('notify', message: 'Service request submitted for operational review.');
    }

    public function openRequestModal()
    {
        $this->reset(['vessel_id', 'eta', 'etd']);
        $this->showModal = true;
    }

    public function openVesselModal()
    {
        $this->reset(['new_vessel_name', 'new_vessel_type', 'new_vessel_imo', 'new_vessel_loa', 'new_vessel_draft']);
        $this->showVesselModal = true;
    }

    public function saveVessel()
    {
        $this->validate([
            'new_vessel_name' => 'required|string',
            'new_vessel_type' => 'required|string',
            'new_vessel_imo' => 'required|string|unique:vessels,imo_number',
            'new_vessel_loa' => 'required|numeric',
            'new_vessel_draft' => 'required|numeric',
        ]);

        Vessel::create([
            'name' => $this->new_vessel_name,
            'organization_id' => $this->agentId,
            'vessel_type' => $this->new_vessel_type,
            'imo_number' => $this->new_vessel_imo,
            'loa_meters' => $this->new_vessel_loa,
            'draft_meters' => $this->new_vessel_draft,
            'flag_country' => 'Malaysia' // Default
        ]);

        $this->showVesselModal = false;
        $this->dispatch('notify', message: 'Vessel registered to fleet!');
    }

    public function getSmartSuggestions()
    {
        if (!$this->vessel_id || !$this->eta || !$this->etd) {
            $this->dispatch('notify', message: 'Please select vessel and dates first!');
            return;
        }

        $vessel = Vessel::find($this->vessel_id);
        if (!$vessel) return;

        $optimizer = new \App\Services\BerthOptimizationService();
        $suggestions = $optimizer->findOptimalBerths($vessel, $this->eta, $this->etd);

        $this->berthSuggestions = array_slice($suggestions, 0, 3);
        $this->showSuggestions = true;

        if (!empty($this->berthSuggestions) && $this->berthSuggestions[0]['available']) {
            $this->selectedSuggestedBerth = $this->berthSuggestions[0]['berth']->id;
        }
    }

    public function selectSuggestedBerth($berthId)
    {
        $this->selectedSuggestedBerth = $berthId;
    }

    public function saveRequest()
    {
        $this->validate();

        PortCall::create([
            'vessel_id' => $this->vessel_id,
            'agent_id' => $this->agentId,
            'assigned_berth_id' => $this->selectedSuggestedBerth,
            'status' => 'requested',
            'eta' => $this->eta,
            'etd' => $this->etd,
            'reference_no' => 'REQ-' . strtoupper(Str::random(6)),
        ]);

        $this->showModal = false;
        $this->activeTab = 'scheduled';
        $this->dispatch('notify', message: 'Berth request submitted successfully!');
    }
    
    // Toggle drill-down section
    public function toggleSection($section)
    {
        if ($this->activeSection === $section) {
            $this->activeSection = null;
        } else {
            $this->activeSection = $section;
        }
    }

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->organization_id && $user->role !== 'admin') {
            $this->agentId = $user->organization_id;
        } else {
            $agent = Organization::where('type', 'agent')->first();
            $this->agentId = $agent ? $agent->id : null;
        }
    }

    public function getFinancialData()
    {
        // 1. Yard / Warehouse Exposure
        $warehouseService = new \App\Services\WarehouseBillingService();
        $yardData = $warehouseService->calculateLiveCharges($this->agentId);
        
        // 2. Asset Rental Exposure
        $activeAssetBookings = \App\Models\AssetBooking::with('asset')
            ->where('organization_id', $this->agentId)
            ->where('status', 'active')
            ->get();
            
        $assetExposure = 0;
        foreach ($activeAssetBookings as $booking) {
            $start = $booking->start_time;
            $now = now();
            // Estimate based on hourly rate for now
            $hours = max(1, $now->diffInHours($start));
            $assetExposure += $hours * ($booking->asset->rate_per_hour ?? 0);
        }

        // 3. Marine / Vessel Exposure (Port Calls)
        $marineService = new \App\Services\BillingService();
        $activePortCalls = PortCall::where('agent_id', $this->agentId)
            ->where('status', 'alongside')
            ->get();
            
        $marineExposure = 0;
        foreach ($activePortCalls as $call) {
            // Update the draft invoice to reflect current time
            $invoice = $marineService->generateInvoice($call);
            $marineExposure += $invoice->total_amount;
        }

        // 4. Invoices History
        $invoices = \App\Models\Invoice::where('organization_id', $this->agentId)
            ->latest()
            ->take(10)
            ->get();
            
        return [
            'yard' => [
                'exposure' => $yardData['total_charges'],
                'count' => $yardData['items_count'],
                'items' => $yardData['items_breakdown']
            ],
            'assets' => [
                'exposure' => $assetExposure,
                'count' => $activeAssetBookings->count(),
                'items' => $activeAssetBookings
            ],
            'marine' => [
                'exposure' => $marineExposure,
                'count' => $activePortCalls->count(),
                'items' => $activePortCalls
            ],
            'total_exposure' => $yardData['total_charges'] + $assetExposure + $marineExposure,
            'invoices' => $invoices
        ];
    }

    public function render()
    {
        if (!$this->agentId) {
            return view('livewire.agent-portal', ['portCalls' => []]);
        }
        
        $params = [];

        if ($this->activeTab === 'commercial') {
            $financialData = $this->getFinancialData();
            $params = array_merge($params, $financialData);
            
        } elseif ($this->activeTab === 'live') {
            // CONSOLIDATED LIVE OPERATIONS
            // 1. Marine: Vessels currently alongside or anchored
            $liveVessels = PortCall::where('agent_id', $this->agentId)
                ->with(['vessel', 'berth', 'invoice'])
                ->whereIn('status', ['anchored', 'alongside', 'approaching'])
                ->orderBy('eta', 'desc')
                ->get();
            
            // 2. Yard: Cargo currently in storage
            $liveYardItems = \App\Models\CargoItem::whereHas('manifest', function($q) {
                $q->where('agent_id', $this->agentId);
            })
            ->with(['zone', 'manifest'])
            ->whereNull('discharged_at')
            ->latest()
            ->get();
            
            // 3. Assets: Equipment currently on rent
            $liveAssets = \App\Models\AssetBooking::with(['asset', 'organization'])
                ->where('organization_id', $this->agentId)
                ->where('status', 'active')
                ->latest()
                ->get();
            
            $params['liveVessels'] = $liveVessels;
            $params['liveYardItems'] = $liveYardItems;
            $params['liveAssets'] = $liveAssets;
            
        } elseif ($this->activeTab === 'scheduled') {
            // SCHEDULED: Berthing requests only
            $params['portCalls'] = PortCall::where('agent_id', $this->agentId)
                ->with(['vessel', 'berth', 'invoice'])
                ->whereIn('status', ['requested', 'approved'])
                ->orderBy('eta', 'desc')
                ->get();
                
        } elseif ($this->activeTab === 'history') {
            // CONSOLIDATED HISTORY
            // 1. Completed Port Calls
            $completedVessels = PortCall::where('agent_id', $this->agentId)
                ->with(['vessel', 'berth', 'invoice'])
                ->whereIn('status', ['completed', 'cancelled'])
                ->orderBy('updated_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function($call) {
                    return [
                        'type' => 'marine',
                        'date' => $call->atd ?? $call->updated_at,
                        'description' => $call->vessel->name,
                        'reference' => $call->reference_no,
                        'status' => $call->status,
                        'amount' => $call->invoice->total_amount ?? 0,
                        'invoice_id' => $call->invoice->id ?? null,
                    ];
                });
            
            // 2. Discharged Cargo
            $dischargedCargo = \App\Models\CargoItem::whereHas('manifest', function($q) {
                $q->where('agent_id', $this->agentId);
            })
            ->with(['manifest'])
            ->whereNotNull('discharged_at')
            ->orderBy('discharged_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'yard',
                    'date' => $item->discharged_at,
                    'description' => $item->tracking_number . ' - ' . $item->description,
                    'reference' => $item->manifest->manifest_no ?? 'N/A',
                    'status' => 'discharged',
                    'amount' => 0, // Would need invoice lookup
                    'invoice_id' => null,
                ];
            });
            
            // 3. Completed Asset Rentals
            $completedAssets = \App\Models\AssetBooking::with(['asset'])
                ->where('organization_id', $this->agentId)
                ->where('status', 'completed')
                ->orderBy('updated_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function($booking) {
                    return [
                        'type' => 'asset',
                        'date' => $booking->end_time ?? $booking->updated_at,
                        'description' => $booking->asset->name,
                        'reference' => $booking->reference_no,
                        'status' => 'completed',
                        'amount' => $booking->total_cost ?? 0,
                        'invoice_id' => null, // Would need invoice lookup
                    ];
                });
            
            // Merge and sort by date
            $allHistory = $completedVessels
                ->concat($dischargedCargo)
                ->concat($completedAssets)
                ->sortByDesc('date')
                ->take(30);
            
            $params['history'] = $allHistory;
        }

        $params['agent'] = Organization::find($this->agentId);
        $params['myVessels'] = Vessel::where('organization_id', $this->agentId)->get();

        return view('livewire.agent-portal', $params)->layout('components.layouts.client');
    }
}
