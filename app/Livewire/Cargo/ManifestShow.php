<?php

namespace App\Livewire\Cargo;

use Livewire\Component;
use App\Models\CargoManifest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ManifestShow extends Component
{
    public CargoManifest $manifest;

    public function mount(CargoManifest $manifest)
    {
        $this->manifest = $manifest->load(['items', 'vessel', 'agent']);
    }

    public function toggleStatus()
    {
        // Simple status workflow for demo
        if ($this->manifest->status === 'draft') {
            $this->manifest->update(['status' => 'submitted']);
        } elseif ($this->manifest->status === 'submitted') {
            $this->manifest->update(['status' => 'approved']);
        } elseif ($this->manifest->status === 'approved') {
            $this->manifest->update(['status' => 'loaded']);
        }
    }

    public function render()
    {
        return view('livewire.cargo.manifest-show');
    }
}
