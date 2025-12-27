<?php

namespace App\Livewire\Assets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PortAsset;
use App\Models\AssetBooking;
use App\Services\AuditService;
use App\Services\WarehouseBillingService;

class Inventory extends Component
{
    use WithPagination;

    public $showAssetModal = false;
    public $showMaintenanceModal = false;
    public $selectedAsset = null;

    // Form fields for PortAsset
    public $assetId;
    public $name, $type = 'crane', $identifier, $rate_per_hour, $rate_per_day, $status = 'available', $description;

    // Maintenance fields
    public $mType = 'Routine', $mDescription, $mPerformedAt, $mNextDue, $mCost, $mTechnician;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string',
        'identifier' => 'required|string|unique:port_assets,identifier',
        'rate_per_hour' => 'nullable|numeric',
        'rate_per_day' => 'nullable|numeric',
        'status' => 'required|in:available,maintenance,occupied,standby',
        'description' => 'nullable|string',
    ];

    public function mount()
    {
        $this->mPerformedAt = now()->format('Y-m-d\TH:i');
    }

    public function openAssetModal($id = null)
    {
        $this->assetId = $id;
        if ($id) {
            $asset = PortAsset::find($id);
            $this->name = $asset->name;
            $this->type = $asset->type;
            $this->identifier = $asset->identifier;
            $this->rate_per_hour = $asset->rate_per_hour;
            $this->rate_per_day = $asset->rate_per_day;
            $this->status = $asset->status;
            $this->description = $asset->description;
        } else {
            $this->reset(['name', 'type', 'identifier', 'rate_per_hour', 'rate_per_day', 'status', 'description']);
        }
        $this->showAssetModal = true;
    }

    public function openMaintenanceModal($assetId)
    {
        $this->assetId = $assetId;
        $this->selectedAsset = PortAsset::with('maintenanceLogs')->find($assetId);
        $this->reset(['mDescription', 'mNextDue', 'mCost', 'mTechnician']);
        $this->mType = 'Routine';
        $this->mPerformedAt = now()->format('Y-m-d\TH:i');
        $this->showMaintenanceModal = true;
    }

    public function saveMaintenance()
    {
        $this->validate([
            'mType' => 'required|string',
            'mDescription' => 'required|string',
            'mPerformedAt' => 'required|date',
            'mTechnician' => 'required|string',
        ]);

        \App\Models\AssetMaintenanceLog::create([
            'port_asset_id' => $this->assetId,
            'type' => $this->mType,
            'description' => $this->mDescription,
            'performed_at' => $this->mPerformedAt,
            'next_service_due' => $this->mNextDue,
            'cost' => $this->mCost,
            'technician_name' => $this->mTechnician,
        ]);

        AuditService::log('Create', 'Maintenance', $this->assetId, "Logged {$this->mType} for {$this->selectedAsset->identifier}");
        
        $this->showMaintenanceModal = false;
        $this->dispatch('notify', message: 'Maintenance record preserved.', type: 'success');
    }

    public function saveAsset()
    {
        $rules = $this->rules;
        if ($this->assetId) {
            $rules['identifier'] = 'required|string|unique:port_assets,identifier,' . $this->assetId;
        }
        $this->validate($rules);

        $assetData = [
            'name' => $this->name,
            'type' => $this->type,
            'identifier' => $this->identifier,
            'rate_per_hour' => $this->rate_per_hour,
            'rate_per_day' => $this->rate_per_day,
            'status' => $this->status,
            'description' => $this->description,
        ];

        if ($this->assetId) {
            PortAsset::find($this->assetId)->update($assetData);
            AuditService::log('Update', 'Assets', $this->assetId, "Updated asset {$this->identifier}");
        } else {
            $asset = PortAsset::create($assetData);
            AuditService::log('Create', 'Assets', $asset->id, "Created new asset {$this->identifier}");
        }

        $this->showAssetModal = false;
        $this->dispatch('notify', message: 'Asset inventory updated successfully.', type: 'success');
    }

    public function approveBooking($bookingId)
    {
        $booking = AssetBooking::find($bookingId);
        $booking->update(['status' => 'active']);
        
        // Update asset status
        $booking->asset->update(['status' => 'occupied']);

        AuditService::log('Update', 'Bookings', $booking->id, "Approved and deployed booking ref: {$booking->reference_no}");
        $this->dispatch('notify', message: 'Booking approved. Resource deployed.', type: 'success');
    }

    public function completeBooking($bookingId)
    {
        $booking = AssetBooking::with('asset')->find($bookingId);
        
        if (!$booking) return;

        // Set end time to now for accurate billing
        $booking->end_time = now();
        $booking->status = 'completed';
        $booking->save();
        
        // Free up the asset
        $booking->asset->update(['status' => 'available']);

        // Generate Invoice
        $billingService = new WarehouseBillingService();
        $invoice = $billingService->createInvoiceFromAssetBooking($booking);

        AuditService::log('Update', 'Bookings', $booking->id, "Completed booking {$booking->reference_no}. Invoice generated: " . ($invoice ? $invoice->invoice_no : 'N/A'));
        
        $this->dispatch('notify', message: 'Resource returned. Billing finalized.', type: 'success');
    }

    public function render()
    {
        return view('livewire.assets.inventory', [
            'assets' => PortAsset::with('currentBooking.organization')->latest()->paginate(10),
            'pendingBookings' => AssetBooking::with(['organization', 'asset'])->where('status', 'requested')->latest()->get(),
            'activeBookings' => AssetBooking::with(['organization', 'asset'])->where('status', 'active')->latest()->get()
        ]);
    }
}
