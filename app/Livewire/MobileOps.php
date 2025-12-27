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
            
        // Fetch Pending Service Tasks
        $pendingServices = ServiceRequest::where('status', 'pending')
            ->with(['portCall.vessel', 'portCall.berth'])
            ->orderBy('requested_at', 'asc')
            ->get();

        return view('livewire.mobile-ops', [
            'bookings' => $bookings,
            'pendingServices' => $pendingServices
        ])->layout('components.layouts.client');
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
        // ... (Optional: redirect to Agent Portal logic or keep as "Ops Requested")
        // For now, let's remove the rapid buttons if we have the full flow, 
        // OR keep them as "Ad-hoc" requests.
        // Let's keep them but make them create pending requests? 
        // No, the previous code auto-delivered. Let's make them create PENDING so we can show the flow.
        
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
