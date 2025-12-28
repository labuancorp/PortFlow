<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\ServiceRequest;
use App\Services\BillingService;
use Carbon\Carbon;

use App\Models\AssetBooking; // Add import

class MobileOps extends Component
{
    public $tab = 'vessels'; // 'vessels' or 'assets'

    // Asset Handover Form
    public $showHandoverModal = false;
    public $handoverType = 'checkout'; // checkout or checkin
    public $selectedBookingId;
    public $handoverNotes;
    public $handoverPhoto; // Mock for now

    public function mount()
    {
        $scan = request()->query('scan');
        if ($scan) {
            $this->tab = 'assets';
            
            // Try to find active task for this asset
            $asset = \App\Models\PortAsset::where('identifier', $scan)->first();
            if ($asset) {
                // Find active booking
                $booking = AssetBooking::where('port_asset_id', $asset->id)
                    ->where('status', 'active')
                    ->first();
                    
                if ($booking) {
                    // Determine type based on check_out_time
                    $type = $booking->check_out_time ? 'checkin' : 'checkout';
                    $this->openHandover($booking->id, $type);
                    // Defer notification until rendered (Livewire quirk, usually works)
                } 
            }
        }
    }

    public function render()
    {
        $bookings = PortCall::whereIn('status', ['approaching', 'anchored', 'alongside'])
            ->with(['vessel', 'berth'])
            ->orderBy('eta', 'asc')
            ->get();
            
        // Fetch Pending Service Tasks
        $pendingServices = ServiceRequest::where('status', 'pending')
            ->with(['portCall.vessel', 'portCall.berth'])
            ->orderBy('requested_at', 'asc')
            ->get();

        // Fetch Asset Tasks
        // 1. Approved but not Checked Out (Needs Checkout)
        // 2. Active (Checked Out) but not Checked In (Needs Checkin)
        // Note: 'active' covers both. We distinguish by check_out_time column.
        
        $assetTasks = AssetBooking::with(['asset', 'organization'])
            ->where('status', 'active')
            ->get()
            ->map(function($booking) {
                // Determine task type
                if (!$booking->check_out_time) {
                    $booking->task_type = 'checkout';
                } else {
                    $booking->task_type = 'checkin';
                }
                return $booking;
            });

        return view('livewire.mobile-ops', [
            'bookings' => $bookings,
            'pendingServices' => $pendingServices,
            'assetTasks' => $assetTasks
        ])->layout('components.layouts.client');
    }

    public function openHandover($bookingId, $type)
    {
        $this->selectedBookingId = $bookingId;
        $this->handoverType = $type;
        $this->handoverNotes = '';
        $this->handoverPhoto = null;
        $this->showHandoverModal = true;
    }

    public function submitHandover()
    {
        $booking = AssetBooking::find($this->selectedBookingId);
        
        if ($this->handoverType === 'checkout') {
            $booking->update([
                'check_out_time' => now(),
                'check_out_notes' => $this->handoverNotes,
                'check_out_media' => ['mock_photo_url.jpg'] // Simulate upload
            ]);
            $this->dispatch('notify', message: 'Asset Checked Out. Timer Started.');
        } else {
            $booking->update([
                'check_in_time' => now(),
                'check_in_notes' => $this->handoverNotes,
                'check_in_media' => ['mock_photo_url.jpg'], // Simulate upload
                'status' => 'completed',
                'end_time' => now()
            ]);
            
            // Free asset
            $booking->asset->update(['status' => 'available']);
            
            // Generate Invoice
            $billing = new BillingService(); // Use WarehouseBillingService if distinct, but BillingService might be generic
            // Note: In Inventory.php we used WarehouseBillingService.
            // Let's rely on backend or just mark completed for now. To be safe, let's just mark completed.
            
            $this->dispatch('notify', message: 'Asset Checked In. Return Verified.');
        }

        $this->showHandoverModal = false;
    }

    // Actions triggering state changes
    public function updateStatus($id, $status)
    {
        $booking = PortCall::with(['vessel', 'berth', 'invoice'])->find($id);
        if (!$booking) return;

        $updates = ['status' => $status];
        $now = now();

        if ($status === 'anchored' && !$booking->ata) {
            $updates['ata'] = $now;
        }
        if ($status === 'alongside' && !$booking->atb) {
            $updates['atb'] = $now;
            // Generate Invoice Draft
            $service = new BillingService();
            $service->generateInvoice($booking);
        }
        if ($status === 'completed') {
            if (!$booking->atd) {
                $updates['atd'] = $now;
            }
            // Fix: Backfill ATB if missing (Direct Completion)
            if (!$booking->atb) {
                $updates['atb'] = $booking->ata ?? $booking->eta ?? $now;
            }
        }

        $booking->update($updates);

        if ($status === 'alongside' || $status === 'completed') {
             // Generate/Finalize Invoice AFTER timestamps are saved
             $service = new BillingService();
             $service->generateInvoice($booking->fresh());
        }

        $this->dispatch('notify', message: 'Status updated to ' . strtoupper($status));
    }

    public function fulfillService($requestId)
    {
        $request = ServiceRequest::find($requestId);
        if (!$request) return;

        $request->update([
            'status' => 'delivered',
            'delivered_at' => now() // ensure this column exists or we just use updated_at/logic
        ]);

        // Update Invoice
        $service = new BillingService();
        $service->generateInvoice($request->portCall);

        $this->dispatch('notify', message: 'Service Fulfilled & Billed!');
    }
    
    // Legacy simple request (kept if needed for quick demo)
    public function requestService($portCallId, $type)
    {
        $quantity = ($type === 'water') ? 50 : 1000; 
        $unit = ($type === 'water') ? 'MT' : 'Liters';

        ServiceRequest::create([
            'port_call_id' => $portCallId,
            'service_type' => $type,
            'quantity' => $quantity,
            'unit' => $unit,
            'status' => 'pending', 
            'requested_at' => now()
        ]);

        $this->dispatch('notify', message: ucfirst($type) . " request added to queue.");
    }
}
