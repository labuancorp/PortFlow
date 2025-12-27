<?php

namespace App\Livewire\Cargo;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CargoManifest;

class ManifestIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $manifest = CargoManifest::find($id);

        if (!$manifest) {
            return;
        }

        // Security Check for Agents
        if (auth()->user()->role === 'agent' && $manifest->agent_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($manifest->status === 'draft') {
            $manifest->delete();
            $this->dispatch('notify', message: 'Manifest deleted successfully.');
        } else {
             $this->dispatch('notify', message: 'Cannot delete processed manifests.', type: 'error');
        }
    }

    public function render()
    {
        $query = CargoManifest::with(['vessel', 'agent'])
            ->withCount('items');

        // Filter for Agents
        if (auth()->user()->role === 'agent') {
            $query->where('agent_id', auth()->user()->organization_id);
        }

        $manifests = $query->when($this->search, function ($q) {
                $q->where('reference_no', 'like', '%'.$this->search.'%')
                  ->orWhereHas('vessel', fn($v) => $v->where('name', 'like', '%'.$this->search.'%'));
            })
            ->latest()
            ->paginate(10);

        return view('livewire.cargo.manifest-index', [
            'manifests' => $manifests
        ]);
    }
}
