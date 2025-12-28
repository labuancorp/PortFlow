<?php

namespace App\Livewire\Mhe;

use Livewire\Component;
use App\Models\MheEquipment;
use App\Services\MheService;
use Livewire\Attributes\Layout;

use Carbon\Carbon;
use App\Models\MheBooking;

class FleetDashboard extends Component
{
    public $selectedAsset;
    public $historyLogs = [];
    public $showHistoryModal = false;
    
    public $showBookingModal = false;
    public $booking_job_type = 'Yard Operation';
    public $booking_start_time;
    public $booking_duration = 4;

    #[Layout('components.layouts.app')]
    public function render(MheService $service)
    {
        $equipment = MheEquipment::orderBy('type')->orderBy('name')->get();
        $stats = $service->getFleetStats();

        return view('livewire.mhe.fleet-dashboard', [
            'equipment' => $equipment,
            'stats' => $stats
        ]);
    }

    public function viewHistory($id)
    {
        $this->selectedAsset = MheEquipment::with('maintenanceLogs')->find($id);
        $this->historyLogs = $this->selectedAsset->maintenanceLogs()->latest()->get();
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->selectedAsset = null;
    }

    public function openBooking($id)
    {
        $this->selectedAsset = MheEquipment::find($id);
        $this->booking_start_time = now()->format('Y-m-d\TH:i');
        $this->showBookingModal = true;
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedAsset = null;
    }

    public function submitBooking()
    {
        $this->validate([
            'booking_job_type' => 'required',
            'booking_start_time' => 'required|date',
            'booking_duration' => 'required|numeric|min:1'
        ]);

        MheBooking::create([
            'equipment_id' => $this->selectedAsset->id,
            'operator_id' => auth()->id(), // Assuming current user is operator/requester
            'job_type' => $this->booking_job_type,
            'start_time' => $this->booking_start_time,
            'end_time' => Carbon::parse($this->booking_start_time)->addHours($this->booking_duration),
            'status' => 'scheduled'
        ]);

        // For demo purposes, if booked "now", set to in-use
        // In real app, a cron would handle this status transition based on time
        $this->selectedAsset->update(['status' => 'in-use']);

        $this->showBookingModal = false;
        $this->selectedAsset = null;
        
        // Flash message or similar could be added here
    }
}
