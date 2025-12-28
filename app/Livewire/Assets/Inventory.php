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
    public $safety_cert_expiry;
    public $billing_mode = 'duration', $current_engine_hours = 0;

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
        'safety_cert_expiry' => 'nullable|date',
        'billing_mode' => 'required|in:duration,telemetry',
        'current_engine_hours' => 'nullable|numeric|min:0',
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
            $this->safety_cert_expiry = $asset->safety_cert_expiry ? $asset->safety_cert_expiry->format('Y-m-d') : null;
            $this->billing_mode = $asset->billing_mode ?? 'duration';
            $this->current_engine_hours = $asset->current_engine_hours ?? 0;
        } else {
            $this->reset(['name', 'type', 'identifier', 'rate_per_hour', 'rate_per_day', 'status', 'description', 'safety_cert_expiry', 'billing_mode', 'current_engine_hours']);
        }
        $this->showAssetModal = true;
    }

    // ... (maintenance methods) ...

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
            'safety_cert_expiry' => $this->safety_cert_expiry,
            'billing_mode' => $this->billing_mode,
            'current_engine_hours' => $this->current_engine_hours,
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
        $booking = AssetBooking::with('asset')->find($bookingId);
        $asset = $booking->asset;

        // 1. Check Safety Certificate
        if ($asset->safety_cert_expiry && $asset->safety_cert_expiry->isPast()) {
            $this->dispatch('notify', message: 'SAFETY ALERT: Asset safety certificate has EXPIRED. Renewal required.', type: 'error');
            return;
        }

        // 2. Check Maintenance Status
        if ($asset->status === 'maintenance') {
            $this->dispatch('notify', message: 'ERROR: Asset is currently under maintenance.', type: 'error');
            return;
        }

        $booking->update(['status' => 'active']);
        
        // Update asset status
        $asset->update(['status' => 'occupied']);

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
