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
    public $editMode = false;

    public $permissionError = false;

    // Edit form properties
    public $editVesselId;
    public $editAgentId;
    public $editBerthId;
    public $editEta;
    public $editEtd;

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
        $this->initializeEditForm();
    }
    
    public function initializeEditForm()
    {
        $this->editVesselId = $this->booking->vessel_id;
        $this->editAgentId = $this->booking->agent_id;
        $this->editBerthId = $this->booking->assigned_berth_id;
        $this->editEta = $this->booking->eta ? $this->booking->eta->format('Y-m-d\TH:i') : '';
        $this->editEtd = $this->booking->etd ? $this->booking->etd->format('Y-m-d\TH:i') : '';
    }

    public function toggleEditMode()
    {
        $this->editMode = !$this->editMode;
        if ($this->editMode) {
            $this->initializeEditForm();
        }
    }

    public function saveEdit()
    {
        $this->validate([
            'editVesselId' => 'required|exists:vessels,id',
            'editAgentId' => 'required|exists:organizations,id',
            'editBerthId' => 'nullable|exists:berths,id',
            'editEta' => 'required|date',
            'editEtd' => 'required|date|after:editEta',
        ]);

        $this->booking->update([
            'vessel_id' => $this->editVesselId,
            'agent_id' => $this->editAgentId,
            'assigned_berth_id' => $this->editBerthId,
            'eta' => $this->editEta,
            'etd' => $this->editEtd,
        ]);

        $this->booking->refresh();
        $this->booking->load(['vessel', 'agent', 'berth']);
        $this->editMode = false;
        
        // Notify parent to refresh
        $this->dispatch('booking-updated');
        
        session()->flash('success', 'Booking updated successfully!');
    }

    public function cancelEdit()
    {
        $this->editMode = false;
        $this->initializeEditForm();
    }
    
    public function refreshInvoice()
    {
        // Always regenerate invoice for live billing (unless it's paid)
        $service = new BillingService();
        $this->invoice = $service->generateInvoice($this->booking);
        
        // Reload the invoice with items to ensure fresh data
        if ($this->invoice) {
            $this->invoice->load('invoiceItems');
            \Log::info('Invoice refreshed', [
                'invoice_id' => $this->invoice->id,
                'status' => $this->invoice->status,
                'items_count' => $this->invoice->invoiceItems->count(),
                'atb' => $this->booking->atb,
            ]);
        } else {
            \Log::warning('Invoice generation returned null');
        }
    }

    public function updateStatus($newStatus)
    {
        if (auth()->user()->role !== 'admin') {
            return;
        }

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

        // Refresh relation with berth loaded
        $this->booking->refresh();
        $this->booking->load(['vessel', 'agent', 'berth']);

        // Recalculate Invoice
        $service = new BillingService();
        $this->invoice = $service->generateInvoice($this->booking);

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
        $vessels = \App\Models\Vessel::orderBy('name')->get();
        $agents = \App\Models\Organization::where('type', 'agent')->orderBy('name')->get();
        $berths = \App\Models\Berth::where('status', 'active')->orderBy('name')->get();
        
        return view('livewire.view-booking', [
            'vessels' => $vessels,
            'agents' => $agents,
            'berths' => $berths,
        ]);
    }
}
