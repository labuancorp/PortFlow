<?php

namespace App\Livewire\Wharfs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Berth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal State
    public $showModal = false;
    public $isEditing = false;
    public $editingBerthId = null;

    // Form Data
    public $wharf_form = [
        'name' => '',
        'code' => '',
        'max_loa' => '',
        'max_draft' => '',
        'status' => 'active',
    ];

    public function render()
    {
        $wharfs = Berth::query()
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('code', 'like', '%'.$this->search.'%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.wharfs.index', [
            'wharfs' => $wharfs
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
        $wharf = Berth::findOrFail($id);
        $this->editingBerthId = $id;
        $this->wharf_form = [
            'name' => $wharf->name,
            'code' => $wharf->code,
            'max_loa' => $wharf->max_loa,
            'max_draft' => $wharf->max_draft,
            'status' => $wharf->status,
        ];
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        try {
            $wharf = Berth::findOrFail($id);
            // Optional: Check for active port calls before deletion?
            // For now, we assume cascade or restriction at DB level, 
            // but let's just delete.
            $wharf->delete();
            session()->flash('success', 'Wharf decommissioned successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Cannot remove active infrastructure.');
        }
    }

    public function save()
    {
        $this->validate([
            'wharf_form.name' => 'required|string|max:50',
            'wharf_form.code' => 'nullable|string|max:20',
            'wharf_form.max_loa' => 'required|numeric|min:0',
            'wharf_form.max_draft' => 'required|numeric|min:0',
            'wharf_form.status' => 'required|in:active,maintenance,occupied',
        ]);

        if ($this->isEditing) {
            $wharf = Berth::find($this->editingBerthId);
            $wharf->update($this->wharf_form);
            session()->flash('success', 'Wharf specifications updated.');
        } else {
            Berth::create($this->wharf_form);
            session()->flash('success', 'New wharf commissioned successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->wharf_form = [
            'name' => '',
            'code' => '',
            'max_loa' => '',
            'max_draft' => '',
            'status' => 'active',
        ];
        $this->editingBerthId = null;
    }
}
