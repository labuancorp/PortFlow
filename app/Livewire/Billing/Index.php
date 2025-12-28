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

    public $activeTab = 'unbilled'; // unbilled, invoices, warehouse, assets
    public $viewingInvoice = null;

    // Pricing Config (Mock)
    private $rate_dockage_per_meter_hour = 2.50; // RM 2.50 per meter per hour
    private $rate_wharfage_fixed = 500.00;

    public function viewInvoice($id)
    {
        $this->viewingInvoice = Invoice::with(['invoiceItems', 'organization', 'portCall.vessel'])->find($id);
    }

    public function closeInvoiceModal()
    {
        $this->viewingInvoice = null;
    }

    public function render()
    {
        // 1. Unbilled Completed Port Calls
        $unbilledQuery = PortCall::where('status', 'completed')
            ->whereDoesntHave('invoice')
            ->with(['vessel', 'agent', 'berth']);

        // 2. Generated Invoices
        $invoiceQuery = Invoice::with(['portCall.vessel', 'organization']);

        // AGENT RESTRICTION: Filter by Organization
        if (auth()->user()->role === 'agent') {
            $orgId = auth()->user()->organization_id;
            
            // Only see port calls handled by this agent's org
            $unbilledQuery->where('agent_id', $orgId);
            
            // Only see invoices billed to this agent's org
            $invoiceQuery->where('organization_id', $orgId);
        }

        $unbilledCalls = $unbilledQuery->orderBy('atd', 'desc')->paginate(5, ['*'], 'unbilledPage');
        $invoices = $invoiceQuery->orderBy('updated_at', 'desc')->paginate(10, ['*'], 'invoicePage');

        // 3. Warehouse Billing (Pending Storage Charges)
        $warehouseBillingService = new \App\Services\WarehouseBillingService();
        $warehouseData = $warehouseBillingService->calculateLiveCharges();
        $warehouseSummary = [
            'total_charges' => $warehouseData['total_charges'],
            'items_count' => $warehouseData['items_count']
        ];
        
        // Convert breakdown to collection with proper attributes
        $warehouseItems = collect($warehouseData['items_breakdown'])->map(function($item) {
            return (object)[
                'tracking_number' => $item['tracking_number'],
                'description' => $item['description'],
                'agent_name' => $item['agent'],
                'storage_days' => $item['days_stored'],
                'pending_charges' => $item['total'],
            ];
        });

        // 4. Asset Rentals (Active Bookings)
        $assetBookings = \App\Models\AssetBooking::where('status', 'active')
            ->with(['asset', 'organization'])
            ->get()
            ->map(function($booking) {
                $hours = max(1, now()->diffInHours($booking->start_time));
                $booking->pending_charges = $hours * ($booking->asset->rate_per_hour ?? 0);
                $booking->rental_hours = $hours;
                return $booking;
            });

        return view('livewire.billing.index', [
            'unbilledCalls' => $unbilledCalls,
            'invoices' => $invoices,
            'warehouseItems' => $warehouseItems,
            'warehouseSummary' => $warehouseSummary,
            'assetBookings' => $assetBookings,
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
