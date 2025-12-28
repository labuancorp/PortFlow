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

    // CRUD properties
    public $showEquipmentModal = false;
    public $editMode = false;
    public $equipment_id;
    public $name;
    public $asset_code;
    public $type = 'Forklift';
    public $model;
    public $manufacturer;
    public $year;
    public $status = 'available';
    public $current_hour_meter = 0;
    public $next_pm_due_hours = 500;
    public $location;

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

    // CRUD Methods
    public function openEquipmentModal()
    {
        $this->resetEquipmentForm();
        $this->editMode = false;
        $this->showEquipmentModal = true;
    }

    public function editEquipment($id)
    {
        $equipment = MheEquipment::findOrFail($id);
        
        $this->equipment_id = $equipment->id;
        $this->name = $equipment->name;
        $this->asset_code = $equipment->asset_code;
        $this->type = $equipment->type;
        $this->model = $equipment->model;
        $this->manufacturer = $equipment->manufacturer;
        $this->year = $equipment->year;
        $this->status = $equipment->status;
        $this->current_hour_meter = $equipment->current_hour_meter;
        $this->next_pm_due_hours = $equipment->next_pm_due_hours;
        $this->location = $equipment->location;
        
        $this->editMode = true;
        $this->showEquipmentModal = true;
    }

    public function saveEquipment()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'asset_code' => 'required|string|max:50|unique:mhe_equipment,asset_code,' . ($this->equipment_id ?? 'NULL'),
            'type' => 'required|string',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:available,in-use,maintenance,breakdown',
            'current_hour_meter' => 'required|numeric|min:0',
            'next_pm_due_hours' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $this->name,
            'asset_code' => $this->asset_code,
            'type' => $this->type,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,
            'year' => $this->year,
            'status' => $this->status,
            'current_hour_meter' => $this->current_hour_meter,
            'next_pm_due_hours' => $this->next_pm_due_hours,
            'location' => $this->location,
        ];

        if ($this->editMode) {
            MheEquipment::findOrFail($this->equipment_id)->update($data);
            session()->flash('success', 'Equipment updated successfully!');
        } else {
            MheEquipment::create($data);
            session()->flash('success', 'Equipment added successfully!');
        }

        $this->closeEquipmentModal();
    }

    public function deleteEquipment($id)
    {
        $equipment = MheEquipment::findOrFail($id);
        $equipment->delete();
        
        session()->flash('success', 'Equipment deleted successfully!');
    }

    public function closeEquipmentModal()
    {
        $this->showEquipmentModal = false;
        $this->resetEquipmentForm();
    }

    private function resetEquipmentForm()
    {
        $this->equipment_id = null;
        $this->name = '';
        $this->asset_code = '';
        $this->type = 'Forklift';
        $this->model = '';
        $this->manufacturer = '';
        $this->year = null;
        $this->status = 'available';
        $this->current_hour_meter = 0;
        $this->next_pm_due_hours = 500;
        $this->location = '';
        $this->resetErrorBag();
    }
}
