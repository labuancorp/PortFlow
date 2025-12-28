<?php

namespace App\Livewire\Assets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PortAsset;
use App\Models\AssetBooking;
use App\Models\AssetMaintenanceLog;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

class UnifiedFleetManagement extends Component
{
    use WithPagination;

    public $activeTab = 'all'; // all, mhe, rentals, maintenance
    public $filterType = '';
    public $filterStatus = '';
    public $search = '';

    // Asset Modal
    public $showAssetModal = false;
    public $editMode = false;
    public $assetId;
    public $name;
    public $type = 'forklift';
    public $identifier;
    public $asset_code;
    public $model;
    public $manufacturer;
    public $year;
    public $location;
    public $rate_per_hour = 0;
    public $rate_per_day = 0;
    public $status = 'available';
    public $description;
    public $safety_cert_expiry;
    public $current_engine_hours = 0;
    public $next_pm_due_hours = 500;
    public $billing_mode = 'duration';

    // Booking Modal
    public $showBookingModal = false;
    public $selectedAsset;
    public $booking_organization_id;
    public $booking_start_time;
    public $booking_duration = 4;
    public $booking_notes;

    // Maintenance Modal
    public $showMaintenanceModal = false;
    public $mType = 'PM';
    public $mDescription;
    public $mTechnician;
    public $mPerformedAt;
    public $mCost = 0;

    #[Layout('components.layouts.app')]
    public function render()
    {
        $query = PortAsset::query();

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('identifier', 'like', '%' . $this->search . '%')
                  ->orWhere('asset_code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // Tab-specific filters
        if ($this->activeTab === 'mhe') {
            $query->whereIn('type', ['forklift', 'crane', 'reach_stacker', 'terminal_tractor', 'container_handler']);
        } elseif ($this->activeTab === 'rentals') {
            $query->whereIn('type', ['warehouse_bay', 'vehicle', 'utility']);
        } elseif ($this->activeTab === 'maintenance') {
            $query->where('status', 'maintenance')
                  ->orWhereNotNull('next_maintenance_date')
                  ->whereDate('next_maintenance_date', '<=', now()->addDays(30));
        }

        $assets = $query->with(['currentBooking.organization', 'maintenanceLogs'])
                        ->orderBy('type')
                        ->orderBy('name')
                        ->paginate(15);

        // Stats
        $stats = [
            'total' => PortAsset::count(),
            'available' => PortAsset::where('status', 'available')->count(),
            'in_use' => PortAsset::where('status', 'occupied')->count(),
            'maintenance' => PortAsset::where('status', 'maintenance')->count(),
            'mhe_count' => PortAsset::whereIn('type', ['forklift', 'crane', 'reach_stacker', 'terminal_tractor', 'container_handler'])->count(),
            'pm_due' => PortAsset::whereNotNull('next_pm_due_hours')
                                 ->whereColumn('current_engine_hours', '>=', 'next_pm_due_hours')
                                 ->count(),
        ];

        $pendingBookings = AssetBooking::where('status', 'pending')
                                       ->with(['asset', 'organization'])
                                       ->latest()
                                       ->take(5)
                                       ->get();

        $activeBookings = AssetBooking::where('status', 'active')
                                      ->with(['asset', 'organization'])
                                      ->latest()
                                      ->take(5)
                                      ->get();

        return view('livewire.assets.unified-fleet-management', [
            'assets' => $assets,
            'stats' => $stats,
            'pendingBookings' => $pendingBookings,
            'activeBookings' => $activeBookings,
        ]);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Asset CRUD Methods
    public function openAssetModal($id = null)
    {
        $this->resetAssetForm();
        
        if ($id) {
            $asset = PortAsset::findOrFail($id);
            $this->assetId = $asset->id;
            $this->name = $asset->name;
            $this->type = $asset->type;
            $this->identifier = $asset->identifier;
            $this->asset_code = $asset->asset_code;
            $this->model = $asset->model;
            $this->manufacturer = $asset->manufacturer;
            $this->year = $asset->year;
            $this->location = $asset->location;
            $this->rate_per_hour = $asset->rate_per_hour;
            $this->rate_per_day = $asset->rate_per_day;
            $this->status = $asset->status;
            $this->description = $asset->description;
            $this->safety_cert_expiry = $asset->safety_cert_expiry?->format('Y-m-d');
            $this->current_engine_hours = $asset->current_engine_hours;
            $this->next_pm_due_hours = $asset->next_pm_due_hours;
            $this->billing_mode = $asset->billing_mode;
            $this->editMode = true;
        }
        
        $this->showAssetModal = true;
    }

    public function saveAsset()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'identifier' => 'required|string|max:100|unique:port_assets,identifier,' . ($this->assetId ?? 'NULL'),
            'rate_per_hour' => 'required|numeric|min:0',
            'rate_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,standby',
        ]);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'identifier' => $this->identifier,
            'asset_code' => $this->asset_code,
            'model' => $this->model,
            'manufacturer' => $this->manufacturer,
            'year' => $this->year,
            'location' => $this->location,
            'rate_per_hour' => $this->rate_per_hour,
            'rate_per_day' => $this->rate_per_day,
            'status' => $this->status,
            'description' => $this->description,
            'safety_cert_expiry' => $this->safety_cert_expiry,
            'current_engine_hours' => $this->current_engine_hours,
            'next_pm_due_hours' => $this->next_pm_due_hours,
            'billing_mode' => $this->billing_mode,
        ];

        if ($this->editMode) {
            PortAsset::findOrFail($this->assetId)->update($data);
            session()->flash('success', 'Asset updated successfully!');
        } else {
            PortAsset::create($data);
            session()->flash('success', 'Asset registered successfully!');
        }

        $this->closeAssetModal();
    }

    public function deleteAsset($id)
    {
        PortAsset::findOrFail($id)->delete();
        session()->flash('success', 'Asset deleted successfully!');
    }

    public function closeAssetModal()
    {
        $this->showAssetModal = false;
        $this->resetAssetForm();
    }

    private function resetAssetForm()
    {
        $this->assetId = null;
        $this->name = '';
        $this->type = 'forklift';
        $this->identifier = '';
        $this->asset_code = '';
        $this->model = '';
        $this->manufacturer = '';
        $this->year = null;
        $this->location = '';
        $this->rate_per_hour = 0;
        $this->rate_per_day = 0;
        $this->status = 'available';
        $this->description = '';
        $this->safety_cert_expiry = '';
        $this->current_engine_hours = 0;
        $this->next_pm_due_hours = 500;
        $this->billing_mode = 'duration';
        $this->editMode = false;
        $this->resetErrorBag();
    }

    // Booking Methods
    public function openBookingModal($id)
    {
        $this->selectedAsset = PortAsset::findOrFail($id);
        $this->booking_start_time = now()->format('Y-m-d\TH:i');
        $this->showBookingModal = true;
    }

    public function approveBooking($bookingId)
    {
        $booking = AssetBooking::findOrFail($bookingId);
        $booking->update(['status' => 'active']);
        $booking->asset->update(['status' => 'occupied']);
        
        session()->flash('success', 'Booking approved and asset deployed!');
    }

    public function completeBooking($bookingId)
    {
        $booking = AssetBooking::findOrFail($bookingId);
        $booking->update([
            'status' => 'completed',
            'end_time' => now(),
        ]);
        $booking->asset->update(['status' => 'available']);
        
        session()->flash('success', 'Booking completed! Invoice can be generated.');
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedAsset = null;
    }

    // Maintenance Methods
    public function openMaintenanceModal($id)
    {
        $this->selectedAsset = PortAsset::with('maintenanceLogs')->findOrFail($id);
        $this->mPerformedAt = now()->format('Y-m-d\TH:i');
        $this->showMaintenanceModal = true;
    }

    public function saveMaintenance()
    {
        $this->validate([
            'mType' => 'required|string',
            'mDescription' => 'required|string',
            'mTechnician' => 'required|string',
            'mPerformedAt' => 'required|date',
            'mCost' => 'required|numeric|min:0',
        ]);

        AssetMaintenanceLog::create([
            'port_asset_id' => $this->selectedAsset->id,
            'type' => $this->mType,
            'description' => $this->mDescription,
            'technician_name' => $this->mTechnician,
            'performed_at' => $this->mPerformedAt,
            'cost' => $this->mCost,
        ]);

        $this->selectedAsset->update([
            'last_maintenance_date' => $this->mPerformedAt,
        ]);

        session()->flash('success', 'Maintenance log recorded successfully!');
        $this->closeMaintenanceModal();
    }

    public function closeMaintenanceModal()
    {
        $this->showMaintenanceModal = false;
        $this->selectedAsset = null;
        $this->mType = 'PM';
        $this->mDescription = '';
        $this->mTechnician = '';
        $this->mPerformedAt = '';
        $this->mCost = 0;
    }
}
