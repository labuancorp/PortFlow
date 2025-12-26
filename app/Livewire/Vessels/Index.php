<?php

namespace App\Livewire\Vessels;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vessel;
use App\Models\Organization;
use App\Services\AuditService;

class Index extends Component
{
// ... (lines 12-90 remain unchanged, handled by partial match logic of the tool or I should include enough context)
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

    public function delete($id)
    {
        $vessel = Vessel::find($id);
        if ($vessel) {
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
            'organization_id' => '',
        ];
        $this->editingVesselId = null;
    }
}
