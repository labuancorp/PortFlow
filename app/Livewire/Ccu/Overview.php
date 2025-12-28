<?php

namespace App\Livewire\Ccu;

use Livewire\Component;
use App\Models\CcuContainer;
use App\Services\CcuService;
use Livewire\Attributes\Layout;

class Overview extends Component
{
    public $search = '';
    
    // Gate In Modal
    public $showGateInModal = false;
    public $container_number;
    public $type = 'Dry';
    public $size = '20ft';
    public $owner;
    public $location_yard_zone;
    public $gate_in_date;
    public $free_days = 7;
    public $sling_cert_expiry;

    #[Layout('components.layouts.app')]
    public function render(CcuService $service)
    {
        $query = CcuContainer::query();
        
        if ($this->search) {
            $query->where('container_number', 'like', '%' . $this->search . '%')
                  ->orWhere('owner', 'like', '%' . $this->search . '%');
        }

        $containers = $query->orderByDesc('gate_in_date')->get();

        // Enrich data
        $containers->each(function($c) use ($service) {
            $c->calc_demurrage = $service->calculateDemurrage($c);
            $c->calc_cert = $service->checkSlingCert($c);
        });

        $stats = [
            'total' => CcuContainer::count(),
            'demurrage' => $containers->filter(fn($c) => $c->calc_demurrage['status'] === 'demurrage')->count(),
            'expired' => $containers->filter(fn($c) => !$c->calc_cert['valid'])->count(),
            'yard_util' => 45 // Demo %
        ];

        return view('livewire.ccu.overview', [
            'containers' => $containers,
            'stats' => $stats
        ]);
    }
    
    public function openGateInModal()
    {
        $this->resetGateInForm();
        $this->gate_in_date = now()->format('Y-m-d\TH:i');
        $this->showGateInModal = true;
    }
    
    public function closeGateInModal()
    {
        $this->showGateInModal = false;
        $this->resetGateInForm();
    }
    
    public function gateIn()
    {
        $this->validate([
            'container_number' => 'required|string|max:50|unique:ccu_containers,container_number',
            'type' => 'required|string',
            'size' => 'required|string',
            'owner' => 'nullable|string|max:255',
            'location_yard_zone' => 'nullable|string|max:50',
            'gate_in_date' => 'required|date',
            'free_days' => 'required|integer|min:0',
            'sling_cert_expiry' => 'nullable|date',
        ]);
        
        CcuContainer::create([
            'container_number' => strtoupper($this->container_number),
            'type' => $this->type,
            'size' => $this->size,
            'owner' => $this->owner,
            'location_yard_zone' => $this->location_yard_zone,
            'gate_in_date' => $this->gate_in_date,
            'free_days' => $this->free_days,
            'sling_cert_expiry' => $this->sling_cert_expiry,
            'status' => 'in_yard',
        ]);
        
        session()->flash('success', 'Container gated in successfully!');
        $this->closeGateInModal();
    }
    
    private function resetGateInForm()
    {
        $this->container_number = '';
        $this->type = 'Dry';
        $this->size = '20ft';
        $this->owner = '';
        $this->location_yard_zone = '';
        $this->gate_in_date = '';
        $this->free_days = 7;
        $this->sling_cert_expiry = '';
        $this->resetErrorBag();
    }
}
