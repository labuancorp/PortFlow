<?php

namespace App\Livewire\Cargo;

use Livewire\Component;
use App\Models\CargoManifest;
use App\Models\Vessel;
use App\Models\Organization;
use Illuminate\Support\Str;
use App\Services\AuditService;

class ManifestCreate extends Component
{
    // Manifest Details
    public $vessel_id = '';
    public $agent_id = '';
    public $reference_no;
    public $type = 'inbound';
    public $eta_etd;

    // Items
    public $items = [];

    public function mount()
    {
        $this->reference_no = 'MNF-' . strtoupper(Str::random(8));
        $this->eta_etd = now()->format('Y-m-d\TH:i');
        
        if (auth()->user()->role === 'agent') {
            $this->agent_id = auth()->user()->organization_id;
        }

        // Start with one empty item
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'tracking_number' => 'TRK-' . strtoupper(Str::random(6)),
            'description' => '',
            'weight_kg' => '',
            'volume_m3' => '',
            'dg_class' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    protected $rules = [
        'vessel_id' => 'required|exists:vessels,id',
        'agent_id' => 'required|exists:organizations,id',
        'reference_no' => 'required|unique:cargo_manifests,reference_no',
        'type' => 'required|in:inbound,outbound',
        'eta_etd' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.tracking_number' => 'required|string|distinct',
        'items.*.description' => 'required|string',
        'items.*.weight_kg' => 'required|numeric|min:0',
        'items.*.volume_m3' => 'nullable|numeric|min:0',
        'items.*.dg_class' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        // Enforce Agent ID validation/override
        if (auth()->user()->role === 'agent') {
            $this->agent_id = auth()->user()->organization_id;
        }

        $manifest = CargoManifest::create([
            'vessel_id' => $this->vessel_id,
            'agent_id' => $this->agent_id,
            'reference_no' => $this->reference_no,
            'type' => $this->type,
            'status' => 'draft',
            'eta_etd' => $this->eta_etd,
        ]);

        foreach ($this->items as $item) {
            $manifest->items()->create([
                'tracking_number' => $item['tracking_number'],
                'description' => $item['description'],
                'weight_kg' => $item['weight_kg'],
                'volume_m3' => $item['volume_m3'] ?: 0,
                'dg_class' => $item['dg_class'],
                'status' => 'pending',
            ]);
        }

        AuditService::log('Create', 'Cargo', $manifest->id, "Created Manifest {$manifest->reference_no} with " . count($this->items) . " items");

        $this->redirect(route('cargo.manifests.index'));
    }

    public function render()
    {
        if (auth()->user()->role === 'agent') {
            $vessels = Vessel::where('organization_id', auth()->user()->organization_id)->orderBy('name')->get();
            $agents = Organization::where('id', auth()->user()->organization_id)->get();
        } else {
            $vessels = Vessel::orderBy('name')->get();
            $agents = Organization::where('type', 'agent')->orderBy('name')->get();
        }

        return view('livewire.cargo.manifest-create', [
            'vessels' => $vessels,
            'agents' => $agents,
        ]);
    }
}
