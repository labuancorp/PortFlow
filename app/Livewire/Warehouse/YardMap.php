<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\CargoItem;

class YardMap extends Component
{
    public function render()
    {
        $warehouses = Warehouse::with(['zones.items'])->get();

        // Aging report: Items older than 90 days that are still in the system (not discharged)
        $agingItems = CargoItem::where('created_at', '<=', now()->subDays(90))
            ->whereNotIn('status', ['discharged', 'completed'])
            ->with(['manifest', 'zone'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.warehouse.yard-map', [
            'warehouses' => $warehouses,
            'agingItems' => $agingItems
        ]);
    }
}
