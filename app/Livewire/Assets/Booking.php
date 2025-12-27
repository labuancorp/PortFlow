<?php

namespace App\Livewire\Assets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PortAsset;
use App\Models\AssetBooking;
use App\Services\AuditService;
use Illuminate\Support\Str;

class Booking extends Component
{
    use WithPagination;

    public $showBookingModal = false;
    
    // Booking Form
    public $port_asset_id;
    public $start_time;
    public $end_time;
    public $notes;
    
    // Preview
    public $estimated_cost = 0;

    protected $rules = [
        'port_asset_id' => 'required|exists:port_assets,id',
        'start_time' => 'required|date|after:now',
        'end_time' => 'nullable|date|after:start_time',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->start_time = now()->addHour()->format('Y-m-d\TH:i');
    }

    public function openBookingModal($assetId = null)
    {
        $this->port_asset_id = $assetId;
        $this->showBookingModal = true;
    }

    public function submitBooking()
    {
        $this->validate();

        $asset = PortAsset::find($this->port_asset_id);
        
        // Simple cost estimation (hourly)
        $cost = 0;
        if ($this->end_time) {
            $hours = (strtotime($this->end_time) - strtotime($this->start_time)) / 3600;
            $cost = $hours * $asset->rate_per_hour;
        }

        $booking = AssetBooking::create([
            'organization_id' => auth()->user()->organization_id,
            'port_asset_id' => $this->port_asset_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'estimated_cost' => $cost,
            'status' => 'requested',
            'reference_no' => 'BK-' . strtoupper(Str::random(8)),
            'notes' => $this->notes
        ]);

        AuditService::log('Create', 'Bookings', $booking->id, "Requested resource: {$asset->name}");

        $this->showBookingModal = false;
        $this->dispatch('notify', message: 'Resource request submitted for approval.', type: 'success');
    }

    public function render()
    {
        $org = auth()->user()->organization;
        
        // Only show assets if organization has the module enabled
        // This check is the "subscription" logic requested by user
        if (!$org->hasModule('resource_booking')) {
             return view('livewire.assets.unsubscribed');
        }

        return view('livewire.assets.booking', [
            'availableAssets' => PortAsset::where('status', 'available')->get(),
            'myBookings' => AssetBooking::with('asset')
                ->where('organization_id', $org->id)
                ->latest()
                ->paginate(10)
        ]);
    }
}
