<?php

namespace App\Livewire\Gate;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\GateEntry;

class PreRegister extends Component
{
    public $driver_name;
    public $driver_ic;
    public $vehicle_plate;
    public $cargo_description;
    
    public $entryDetails = null;

    protected $rules = [
        'driver_name' => 'required|string|min:3',
        'driver_ic' => 'required|string|min:5',
        'vehicle_plate' => 'required|string|min:2',
        'cargo_description' => 'required|string|min:3', // Blueprint requirement
    ];

    #[Layout('components.layouts.app')] 
    public function render()
    {
        return view('livewire.gate.pre-register');
    }

    public function register()
    {
        $this->validate();

        $entry = GateEntry::create([
            'driver_name' => $this->driver_name,
            'driver_ic' => $this->driver_ic,
            'vehicle_plate' => $this->vehicle_plate,
            'cargo_description' => $this->cargo_description,
            'status' => 'pending',
        ]);
        
        $this->entryDetails = [
            'uuid' => $entry->uuid,
            'driver_name' => $entry->driver_name,
            'vehicle_plate' => $entry->vehicle_plate,
        ];
        
        // Reset form but keep entry for display
        $this->reset(['driver_name', 'driver_ic', 'vehicle_plate', 'cargo_description']);
    }
}
