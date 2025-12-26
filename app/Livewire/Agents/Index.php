<?php

namespace App\Livewire\Agents;

use Livewire\Component;
use App\Models\Organization;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $isEdit = false;
    public $editId = null;
    
    // Form Fields
    public $name = '';
    public $code = '';
    public $billing_address = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:organizations,code',
        'billing_address' => 'required|string',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'code', 'billing_address', 'isEdit', 'editId']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $agent = Organization::findOrFail($id);
        $this->editId = $id;
        $this->name = $agent->name;
        $this->code = $agent->code;
        $this->billing_address = $agent->billing_address;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:organizations,code,' . $this->editId,
                'billing_address' => 'required|string',
            ]);

            $agent = Organization::findOrFail($this->editId);
            $agent->update([
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'billing_address' => $this->billing_address,
            ]);
            
            AuditService::log('Update', 'Agents', $agent->id, "Updated agent profile for {$agent->name}");

            $this->dispatch('notify', message: 'Agent updated successfully!');
        } else {
            $this->validate();

            $agent = Organization::create([
                'type' => 'agent',
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'billing_address' => $this->billing_address,
            ]);
            
            AuditService::log('Create', 'Agents', $agent->id, "Registered new agent {$agent->name}");

            $this->dispatch('notify', message: 'New Agent registered successfully!');
        }

        $this->showModal = false;
        $this->reset(['name', 'code', 'billing_address', 'isEdit', 'editId']);
    }

    public function delete($id)
    {
        $agent = Organization::findOrFail($id);
        
        // Prevent deletion if linked to data
        if ($agent->portCalls()->exists() || $agent->vessels()->exists()) {
            $this->dispatch('notify', message: 'Cannot delete agent with active history.', type: 'error');
            return;
        }

        $name = $agent->name;
        $agent->delete();
        AuditService::log('Delete', 'Agents', $id, "Deleted agent {$name}");
        $this->dispatch('notify', message: 'Agent profile deleted.');
    }

    public function render()
    {
        $agents = Organization::where('type', 'agent')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.agents.index', [
            'agents' => $agents
        ]);
    }
}
