<?php

namespace App\Livewire\TankFarm;

use Livewire\Component;
use App\Models\Tank;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    public $viewMode = '3d'; // 3d or grid
    public $showTransferModal = false;
    public $showAnalyticsModal = false;
    
    // Transfer form
    public $transfer_from_tank;
    public $transfer_to_tank;
    public $transfer_volume;
    
    #[Layout('components.layouts.app')]
    public function render()
    {
        $equipment = Tank::with('product')->orderBy('name')->get();

        // Calculate statistics
        $totalCapacity = $equipment->sum('capacity_volume');
        $totalVolume = $equipment->sum('current_volume');
        $utilization = $totalCapacity > 0 ? ($totalVolume / $totalCapacity) * 100 : 0;

        $stats = [
            'utilization' => $utilization,
            'total_volume' => $totalVolume,
            'total_capacity' => $totalCapacity,
            'tank_count' => $equipment->count(),
        ];

        return view('livewire.tank-farm.dashboard', [
            'equipment' => $equipment,
            'stats' => $stats,
        ]);
    }
    
    public function switchView($mode)
    {
        $this->viewMode = $mode;
    }
    
    public function openTransferModal()
    {
        $this->showTransferModal = true;
    }
    
    public function openTransferFrom($tankId)
    {
        $this->transfer_from_tank = $tankId;
        $this->showTransferModal = true;
    }
    
    public function closeTransferModal()
    {
        $this->showTransferModal = false;
        $this->reset(['transfer_from_tank', 'transfer_to_tank', 'transfer_volume']);
    }
    
    public function openAnalytics()
    {
        $this->showAnalyticsModal = true;
    }
    
    public function closeAnalytics()
    {
        $this->showAnalyticsModal = false;
    }
    
    public function submitTransfer()
    {
        $this->validate([
            'transfer_from_tank' => 'required|exists:tanks,id',
            'transfer_to_tank' => 'required|exists:tanks,id|different:transfer_from_tank',
            'transfer_volume' => 'required|numeric|min:1',
        ]);
        
        $fromTank = Tank::find($this->transfer_from_tank);
        $toTank = Tank::find($this->transfer_to_tank);
        
        // Check if source has enough volume
        if ($fromTank->current_volume < $this->transfer_volume) {
            session()->flash('error', 'Insufficient volume in source tank');
            return;
        }
        
        // Check if destination has capacity
        if (($toTank->current_volume + $this->transfer_volume) > $toTank->capacity_volume) {
            session()->flash('error', 'Destination tank capacity exceeded');
            return;
        }
        
        // Perform transfer
        $fromTank->decrement('current_volume', $this->transfer_volume);
        $toTank->increment('current_volume', $this->transfer_volume);
        $toTank->update(['current_product_id' => $fromTank->current_product_id]);
        
        session()->flash('success', 'Transfer completed successfully');
        $this->closeTransferModal();
    }
}
