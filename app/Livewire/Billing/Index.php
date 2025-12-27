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
            // For demo flexibility, we might allow generating partial invoices, 
            // but the Service enforces ATB. Let's warn if missing but attempt.
            if (!$portCall->atb) {
                session()->flash('error', 'Cannot generate invoice: Missing berthing time (ATB).');
                return;
            }
        }

        // Use the centralized Billing Service
        $service = new \App\Services\BillingService();
        $invoice = $service->generateInvoice($portCall);

        $this->activeTab = 'invoices';
        session()->flash('success', "Invoice {$invoice->invoice_no} generated successfully via BillingService!");
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
