<?php

namespace App\Livewire\Vessels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vessel;
use App\Models\Organization;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    
    // Modal State
    public $showModal = false;
    public $isEditing = false;
    public $editingVesselId = null;

    // Form Data
    public $vessel_form = [
        'name' => '',
        'imo_number' => '',
        'flag_country' => '',
        'loa_meters' => '',
        'draft_meters' => '',
        'vessel_type' => 'OSV',
        'organization_id' => '',
    ];

    public function render()
    {
        $vessels = Vessel::query()
            ->with('organization')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('imo_number', 'like', '%'.$this->search.'%');
            })
            ->when($this->typeFilter, function($q) {
                $q->where('vessel_type', $this->typeFilter);
            })
            ->orderBy('name')
            ->paginate(10);

        $organizations = Organization::orderBy('name')->get();

        return view('livewire.vessels.index', [
            'vessels' => $vessels,
            'organizations' => $organizations
        ]);
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

    public function save()
    {
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
            $vessel->update($this->vessel_form);
            session()->flash('success', 'Vessel updated successfully.');
        } else {
            Vessel::create($this->vessel_form);
            session()->flash('success', 'Vessel registered successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        $vessel = Vessel::find($id);
        if ($vessel) {
            $vessel->delete();
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
            'organization_id' => '',
        ];
        $this->editingVesselId = null;
    }
}
