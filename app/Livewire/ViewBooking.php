<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Invoice;
use App\Services\BillingService;
use Carbon\Carbon;

class ViewBooking extends Component
{
    public $booking; // The PortCall
    public $invoice = null;
    public $isOpen = true;

    public $permissionError = false;

    public function mount($booking)
    {
        // Binding the model automatically
        $this->booking = PortCall::with(['vessel', 'agent', 'berth', 'invoice.invoiceItems'])->find($booking);

        // Security Check: Agents can only view their own bookings
        if (auth()->user()->role === 'agent' && (int) auth()->user()->organization_id !== (int) $this->booking->agent_id) {
            $this->permissionError = true;
            return; // Stop processing invoice or sensitive data
        }

        $this->refreshInvoice();
    }
    
    public function refreshInvoice()
    {
        if ($this->booking->invoice) {
            $this->invoice = $this->booking->invoice;
        } else {
            // Generate a draft preview if possible
            $service = new BillingService();
            $this->invoice = $service->generateInvoice($this->booking);
        }
    }

    public function updateStatus($newStatus)
    {
        $now = now();
        $updates = ['status' => $newStatus];

        if ($newStatus === 'anchored' && !$this->booking->ata) {
            $updates['ata'] = $now;
        }
        if ($newStatus === 'alongside' && !$this->booking->atb) {
            $updates['atb'] = $now;
        }
        if ($newStatus === 'completed') {
            if (!$this->booking->atd) {
                $updates['atd'] = $now;
            }
            // Fix: If jumping straight to Completed, ensure ATB exists for Billing
            if (!$this->booking->atb) {
                // Determine a reasonable ATB backfill
                // 1. If we have ATA, use that (or add 30 mins to ATA)
                // 2. Fallback to ETA
                // 3. Fallback to NOW (0 duration, but ensures invoice creation)
                $updates['atb'] = $this->booking->ata ?? $this->booking->eta ?? $now;
            }
        }

        $this->booking->update($updates);

        // Recalculate Invoice
        $service = new BillingService();
        $this->invoice = $service->generateInvoice($this->booking);
        
        // Refresh relation
        $this->booking->refresh();

        // Notify Parent to Refresh Schedule
        $this->dispatch('booking-updated');
    }


    public function close()
    {
        $this->isOpen = false;
        $this->dispatch('close-booking-modal');
    }

    public function render()
    {
        return view('livewire.view-booking');
    }
}
