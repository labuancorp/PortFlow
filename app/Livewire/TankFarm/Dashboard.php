<?php

namespace App\Livewire\TankFarm;

use Livewire\Component;
use App\Models\Tank;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        $tanks = Tank::with('product')->orderBy('name')->get();

        // Calculate statistics
        $totalCapacity = $tanks->sum('capacity_volume');
        $totalUtilized = $tanks->sum('current_volume');
        $utilizationRate = $totalCapacity > 0 ? ($totalUtilized / $totalCapacity) * 100 : 0;

        return view('livewire.tank-farm.dashboard', [
            'tanks' => $tanks,
            'totalCapacity' => $totalCapacity,
            'totalUtilized' => $totalUtilized,
            'utilizationRate' => $utilizationRate,
        ]);
    }
}
