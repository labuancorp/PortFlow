<?php

namespace App\Livewire\Vessels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vessel;
use App\Models\Organization;
use App\Services\AuditService;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = 'all';
    public $showModal = false;
    public $isEditing = false;
    public $editingVesselId = null;

    public $vessel_form = [
        'name' => '',
        'imo_number' => '',
        'flag_country' => '',
        'loa_meters' => '',
        'draft_meters' => '',
        'vessel_type' => 'OSV',
        'organization_id' => '',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function save()
    {
        // Enforce Organization ID for Agents
        if (auth()->user()->role === 'agent') {
            $this->vessel_form['organization_id'] = auth()->user()->organization_id;
        }

        $this->validate([
            'vessel_form.name' => 'required|string|max:255',
            'vessel_form.imo_number' => 'required|string|max:20|unique:vessels,imo_number,' . ($this->editingVesselId ?? 'NULL'),
            'vessel_form.loa_meters' => 'required|numeric|min:0',
            'vessel_form.draft_meters' => 'required|numeric|min:0',
            'vessel_form.organization_id' => 'required|exists:organizations,id',
            'vessel_form.flag_country' => 'required|string',
            'vessel_form.vessel_type' => 'required|string'
        ]);

        if ($this->isEditing) {
            $vessel = Vessel::find($this->editingVesselId);
            
            // Security Check
            if (auth()->user()->role === 'agent' && $vessel->organization_id != auth()->user()->organization_id) {
                abort(403, 'Unauthorized action.');
            }

            $vessel->update($this->vessel_form);
            AuditService::log('Update', 'Vessels', $vessel->id, "Updated vessel details for {$vessel->name}");
            session()->flash('success', 'Vessel updated successfully.');
        } else {
            $vessel = Vessel::create($this->vessel_form);
            AuditService::log('Create', 'Vessels', $vessel->id, "Registered new vessel {$vessel->name}");
            session()->flash('success', 'Vessel registered successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $vessel = Vessel::findOrFail($id);

        if (auth()->user()->role === 'agent' && $vessel->organization_id != auth()->user()->organization_id) {
            abort(403, 'Unauthorized access.');
        }

        $this->editingVesselId = $id;
        $this->vessel_form = [
            'name' => $vessel->name,
            'imo_number' => $vessel->imo_number,
            'flag_country' => $vessel->flag_country,
            'loa_meters' => $vessel->loa_meters,
            'draft_meters' => $vessel->draft_meters,
            'vessel_type' => $vessel->vessel_type,
            'organization_id' => $vessel->organization_id,
        ];
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function render()
    {
        $query = Vessel::with('organization')
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('imo_number', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterType !== 'all', function($query) {
                $query->where('vessel_type', $this->filterType);
            });

        // Filter for Agents
        if (auth()->user()->role === 'agent') {
            $query->where('organization_id', auth()->user()->organization_id);
        }

        $vessels = $query->latest()->paginate(10);

        return view('livewire.vessels.index', [
            'vessels' => $vessels,
            'organizations' => Organization::all()
        ]);
    }

    public function delete($id)
    {
        $vessel = Vessel::find($id);
        if ($vessel) {
            // Security Check
            if (auth()->user()->role === 'agent' && $vessel->organization_id != auth()->user()->organization_id) {
                session()->flash('error', 'Unauthorized action.');
                return;
            }

            $name = $vessel->name;
            $vessel->delete();
            AuditService::log('Delete', 'Vessels', $id, "Deleted vessel {$name}");
            session()->flash('success', 'Vessel removed from registry.');
        }
    }

    private function resetForm()
    {
        $this->vessel_form = [
            'name' => '',
            'imo_number' => '',
            'flag_country' => '',
            'loa_meters' => '',
            'draft_meters' => '',
            'vessel_type' => 'OSV',
            'organization_id' => auth()->user()->role === 'agent' ? auth()->user()->organization_id : '',
        ];
        $this->editingVesselId = null;
    }
}
