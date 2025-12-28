<?php

namespace App\Livewire\Ccu;

use Livewire\Component;
use App\Models\CcuContainer;
use App\Services\CcuService;
use Livewire\Attributes\Layout;

class Overview extends Component
{
    public $search = '';

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
}
