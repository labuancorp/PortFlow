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
    public $activeTab = 'live'; // live, scheduled, history
    
    // Modal State
    public $showModal = false;
    public $showVesselModal = false;
    
    // Request Form Fields
    public $vessel_id;
    public $eta;
    public $etd;
    public $draft_arrival;
    public $draft_departure;

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
        $this->reset(['vessel_id', 'eta', 'etd', 'draft_arrival', 'draft_departure']);
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
            'organization_id' => null, // Deprecated owner field
            'agent_id' => $this->agentId, // IMPORTANT: Link to this agent
            'vessel_type' => $this->new_vessel_type,
            'imo_number' => $this->new_vessel_imo,
            'loa_meters' => $this->new_vessel_loa,
            'draft_meters' => $this->new_vessel_draft,
            'status' => 'active'
        ]);

        $this->showVesselModal = false;
        $this->dispatch('notify', message: 'Vessel registered to fleet!');
    }

    public function getSmartSuggestions()
    {
        // Validate that we have the required fields
        if (!$this->vessel_id || !$this->eta || !$this->etd) {
            $this->dispatch('notify', message: 'Please select vessel and dates first!');
            return;
        }

        $vessel = Vessel::find($this->vessel_id);
        
        if (!$vessel) {
            return;
        }

        // Use AI optimization service
        $optimizer = new \App\Services\BerthOptimizationService();
        $suggestions = $optimizer->findOptimalBerths($vessel, $this->eta, $this->etd);

        // Take top 3 suggestions
        $this->berthSuggestions = array_slice($suggestions, 0, 3);
        $this->showSuggestions = true;

        // Auto-select the best suggestion if available
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
            'assigned_berth_id' => $this->selectedSuggestedBerth, // Use AI suggestion if selected
            'status' => 'requested',
            'eta' => $this->eta,
            'etd' => $this->etd,
            'reference_no' => 'REQ-' . strtoupper(Str::random(6)),
        ]);

        $this->showModal = false;
        $this->activeTab = 'scheduled'; // Switch tab to show new request
        $this->dispatch('notify', message: 'Berth request submitted successfully!');
    }

    public function mount()
    {
        // For Demo purposes: Use authenticated user's org
        // UNLESS the user is an Admin/Authority, then show the Agent view for demo
        $user = Auth::user();
        if ($user && $user->organization_id && $user->role !== 'admin') {
            $this->agentId = $user->organization_id;
        } else {
            // Fallback for Admins or Guests: Show the first agent's view
            $agent = Organization::where('type', 'agent')->first();
            $this->agentId = $agent ? $agent->id : null;
        }
    }

    public function render()
    {
        if (!$this->agentId) {
            return view('livewire.agent-portal', ['portCalls' => []]);
        }

        $query = PortCall::where('agent_id', $this->agentId)
            ->with(['vessel', 'berth', 'invoice']);

        if ($this->activeTab === 'live') {
            $query->whereIn('status', ['anchored', 'alongside', 'approaching']);
        } elseif ($this->activeTab === 'scheduled') {
            $query->whereIn('status', ['requested', 'approved']);
        } elseif ($this->activeTab === 'history') {
            $query->whereIn('status', ['completed', 'cancelled']);
        }

        return view('livewire.agent-portal', [
            'portCalls' => $query->orderBy('eta', 'desc')->get(),
            'agent' => Organization::find($this->agentId),
            'myVessels' => Vessel::where('agent_id', $this->agentId)->get() // Only show vessels linked to this agent
        ])->layout('components.layouts.client');
    }
}
