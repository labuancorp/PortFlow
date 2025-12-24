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

    public function mount($booking)
    {
        // Binding the model automatically
        $this->booking = PortCall::with(['vessel', 'agent', 'berth', 'invoice.invoiceItems'])->find($booking);
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
        if ($newStatus === 'completed' && !$this->booking->atd) {
            $updates['atd'] = $now;
        }

        $this->booking->update($updates);

        // Recalculate Invoice
        $service = new BillingService();
        $this->invoice = $service->generateInvoice($this->booking);
        
        // Refresh relation
        $this->booking->refresh();
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
