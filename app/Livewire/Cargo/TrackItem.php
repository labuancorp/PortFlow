<?php

namespace App\Livewire\Cargo;

use Livewire\Component;
use App\Models\CargoItem;
use Livewire\Attributes\Layout;

class TrackItem extends Component
{
    public $trackingNumber;
    public $item;

    public function mount($tracking_number)
    {
        $this->trackingNumber = $tracking_number;
        $this->item = CargoItem::where('tracking_number', $tracking_number)
            ->with(['manifest.vessel', 'manifest.agent', 'zone.warehouse'])
            ->firstOrFail();
    }

    #[Layout('components.layouts.guest')] // Use a guest layout if available, or create a simple one
    public function render()
    {
        return view('livewire.cargo.track-item');
    }
}
