<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\ServiceRequest;
use App\Services\BillingService;
use Carbon\Carbon;

class MobileOps extends Component
{
    // Fetch bookings relevant to ground ops (Anchored, Approaching, Alongside)
    
    public function render()
    {
        $bookings = PortCall::whereIn('status', ['approaching', 'anchored', 'alongside'])
            ->with(['vessel', 'berth'])
            ->orderBy('eta', 'asc')
            ->get();

        return view('livewire.mobile-ops', [
            'bookings' => $bookings
        ])->layout('components.layouts.client'); // Use the clean mobile layout
    }

    // Actions triggering state changes
    public function updateStatus($id, $status)
    {
        $booking = PortCall::find($id);
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
        if ($status === 'completed' && !$booking->atd) {
            $updates['atd'] = $now;
            // Finalize Invoice
            $service = new BillingService();
            $service->generateInvoice($booking);
        }

        $booking->update($updates);
    }

    public function requestService($portCallId, $type)
    {
        $quantity = ($type === 'water') ? 50 : 1000; // Mock quantity input
        $unit = ($type === 'water') ? 'MT' : 'Liters';

        ServiceRequest::create([
            'port_call_id' => $portCallId,
            'service_type' => $type,
            'quantity' => $quantity,
            'unit' => $unit,
            'status' => 'delivered', // Assume immediate delivery for demo
            'requested_at' => now()
        ]);

        // Update Invoice
        $booking = PortCall::find($portCallId);
        $service = new BillingService();
        $service->generateInvoice($booking);

        $this->dispatch('notify', message: ucfirst($type) . " request logged!");
    }
}
