<?php

namespace App\Livewire\Billing;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\PortCall;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public $activeTab = 'unbilled'; // unbilled, invoices

    // Pricing Config (Mock)
    private $rate_dockage_per_meter_hour = 2.50; // RM 2.50 per meter per hour
    private $rate_wharfage_fixed = 500.00;

    public function render()
    {
        // 1. Unbilled Completed Port Calls
        $unbilledCalls = PortCall::where('status', 'completed')
            ->whereDoesntHave('invoice') // Assuming relationship defined in PortCall
            ->with(['vessel', 'agent', 'berth'])
            ->orderBy('atd', 'desc')
            ->paginate(5, ['*'], 'unbilledPage');

        // 2. Generated Invoices
        $invoices = Invoice::with(['portCall.vessel', 'organization'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'invoicePage');

        return view('livewire.billing.index', [
            'unbilledCalls' => $unbilledCalls,
            'invoices' => $invoices
        ]);
    }

    public function generateInvoice($portCallId)
    {
        $portCall = PortCall::findOrFail($portCallId);

        if (!$portCall->atb || !$portCall->atd) {
            session()->flash('error', 'Cannot generate invoice: Missing arrival (ATB) or departure (ATD) timestamps.');
            return;
        }

        // Calculate Duration (Hours, rounded up)
        $durationHours = ceil($portCall->atb->diffInHours($portCall->atd, false)); // false allows float, but we ceil it
        if ($durationHours < 1) $durationHours = 1;

        // Calculate Items
        $items = [];
        
        // 1. Dockage
        $dockageTotal = $portCall->vessel->loa_meters * $durationHours * $this->rate_dockage_per_meter_hour;
        $items[] = [
            'description' => "Dockage Charges ({$portCall->vessel->loa_meters}m x {$durationHours} hrs @ RM{$this->rate_dockage_per_meter_hour})",
            'quantity' => $durationHours,
            'unit_price' => $portCall->vessel->loa_meters * $this->rate_dockage_per_meter_hour, // Price per hour unit
            'total_price' => $dockageTotal
        ];

        // 2. Wharfage
        $items[] = [
            'description' => 'Fixed Wharfage Fee',
            'quantity' => 1,
            'unit_price' => $this->rate_wharfage_fixed,
            'total_price' => $this->rate_wharfage_fixed
        ];

        // 3. Services (Mocked for now as we don't have service requests seeding fully yet)
        // In real app: foreach($portCall->serviceRequests as $req) ...
        
        $grandTotal = collect($items)->sum('total_price');

        // Create Invoice
        $invoice = Invoice::create([
            'port_call_id' => $portCall->id,
            'organization_id' => $portCall->agent_id, // Invoice to Agent
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'total_amount' => $grandTotal,
            'status' => 'draft',
            'issued_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(30),
        ]);

        // Create Items
        foreach ($items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price']
            ]);
        }

        $this->activeTab = 'invoices';
        session()->flash('success', "Invoice {$invoice->invoice_no} generated successfully!");
    }

    public function markAsPaid($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        $invoice->update(['status' => 'paid']);
        session()->flash('success', 'Invoice marked as PAID.');
    }

    public function syncToErp($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $syncer = new \App\Services\ErpSyncService();
        
        $result = $syncer->syncInvoice($invoice);

        if ($result['success']) {
            session()->flash('success', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }

    public function downloadErpPayload($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $syncer = new \App\Services\ErpSyncService();
        $xml = $syncer->generateXmlPayload($invoice);

        return response()->streamDownload(function () use ($xml) {
            echo $xml;
        }, "erp_payload_{$invoice->invoice_no}.xml");
    }
}
