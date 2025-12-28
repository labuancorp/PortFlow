<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Berth;
use App\Models\Vessel;
use App\Models\Invoice;
use App\Models\ServiceRequest;
use App\Services\WarehouseBillingService;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $mode = 'admin'; // 'admin' or 'agent'
    public $now;
    
    // Modal States
    public $showUnpaidModal = false;
    public $showReviewModal = false;
    public $showAlertsModal = false;
    
    // Data for Modals
    public $unpaidInvoicesList = [];
    public $selectedBooking = null;
    public $selectedBerthId = null;
    public $reviewNote = '';
    public $activeAlerts = [];

    public function mount()
    {
        $this->now = Carbon::now();
        $this->mode = auth()->user()->role === 'agent' ? 'agent' : 'admin';
        $this->initializeAlerts();
    }

    private function initializeAlerts()
    {
        $this->activeAlerts = [
            ['id' => 1, 'type' => 'critical', 'title' => 'High Tide Warning', 'message' => 'Swells exceeding 2.5m expected at 18:00 HRS. All vessels on standby.', 'time' => '10 mins ago'],
            ['id' => 2, 'type' => 'warning', 'title' => 'Berth 2 Maintenance', 'message' => 'Scheduled dredging operations tomorrow 09:00 - 12:00. No bookings allowed.', 'time' => '2 hours ago'],
            ['id' => 3, 'type' => 'info', 'title' => 'New Bunkering Policy', 'message' => 'Updated safety protocols for fuel transfer at Wharf 3.', 'time' => '5 hours ago'],
        ];
    }

    public function openUnpaidModal()
    {
        $this->unpaidInvoicesList = Invoice::where('status', '!=', 'paid')
            ->with('organization')
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showUnpaidModal = true;
    }

    public function closeModal()
    {
        $this->showUnpaidModal = false;
        $this->showReviewModal = false;
        $this->showAlertsModal = false;
    }

    public function approveInvoice($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice && $invoice->status === 'draft') {
            $invoice->update(['status' => 'issued', 'issued_date' => now(), 'due_date' => now()->addDays(30)]);
            $this->openUnpaidModal();
            session()->flash('success', 'Invoice #' . $invoice->invoice_no . ' has been issued.');
        }
    }

    public function markInvoicePaid($invoiceId)
    {
        $invoice = Invoice::find($invoiceId);
        if ($invoice) {
            $invoice->update(['status' => 'paid']);
            $this->openUnpaidModal();
            session()->flash('success', 'Invoice marked as paid.');
        }
    }

    public function exportReport()
    {
        session()->flash('success', 'Operational snapshot exported successfully.');
    }

    public function logIncident()
    {
        session()->flash('success', 'Incident log opened. Please contact HSE department.');
    }

    public function toggleAlertsModal()
    {
        $this->showAlertsModal = !$this->showAlertsModal;
    }

    public function openReviewModal($bookingId)
    {
        $this->selectedBooking = PortCall::with(['vessel', 'agent'])->find($bookingId);
        $this->showReviewModal = true;
    }

    public function approveBooking()
    {
        if (!$this->selectedBerthId) {
            $this->addError('selectedBerthId', 'Please select a berth');
            return;
        }

        $this->selectedBooking->update([
            'status' => 'approved',
            'assigned_berth_id' => $this->selectedBerthId
        ]);

        $this->closeModal();
        session()->flash('success', 'Arrival request approved & berth assigned.');
    }

    public function rejectBooking()
    {
        $this->selectedBooking->update(['status' => 'rejected']);
        $this->closeModal();
        session()->flash('success', 'Arrival request rejected.');
    }

    public function render()
    {
        $this->now = Carbon::now();
        $user = auth()->user();

        // Safe defaults to prevent "Undefined variable" errors
        $viewData = [
            'mode' => $this->mode,
            'stats' => [
                'alongside' => 0,
                'expected_arrivals' => 0,
                'occupancy_rate' => 0,
                'completed_month' => 0,
                'active_vessels' => 0,
                'pending_requests' => 0,
                'unpaid_invoices' => 0,
            ],
            'pendingBilling' => ['total_pending' => 0, 'berthing_pending' => 0, 'warehouse_pending' => 0, 'assets_pending' => 0, 'count' => 0],
            'unpaidInvoices' => ['total' => 0, 'count' => 0],
            'activeServiceRequests' => 0,
            'warehouseSummary' => ['total_items' => 0, 'total_charges' => 0],
            'recentActivity' => collect(),
            'topAgents' => collect(),
            'pendingRequests' => collect(),
            'activeBerths' => collect(),
            'liveBilling' => ['total_charges' => 0, 'berthing_charges' => 0, 'warehouse_charges' => 0, 'assets_charges' => 0, 'berthing_vessels' => 0],
            'now' => $this->now
        ];

        if ($this->mode === 'agent') {
            $data = $this->renderAgentDashboard($user, $this->now);
        } else {
            $data = $this->renderAdminDashboard($this->now);
        }

        return view('livewire.dashboard', array_merge($viewData, $data));
    }

    private function renderAdminDashboard($now)
    {
        // KPI: Vessels Alongside
        $alongsideCount = PortCall::where('status', 'alongside')->count();

        // KPI: Expected Arrivals (24h)
        $expectedArrivals = PortCall::whereIn('status', ['requested', 'approved'])
            ->whereBetween('eta', [$now, $now->copy()->addHours(24)])
            ->count();

        // KPI: Berth Occupancy
        $activeBerths = Berth::where('status', 'active')->get();
        $totalBerthsCount = $activeBerths->count();
        $occupiedBerthsCount = PortCall::where('status', 'alongside')->distinct('assigned_berth_id')->count();
        $occupancyRate = $totalBerthsCount > 0 ? round(($occupiedBerthsCount / $totalBerthsCount) * 100) : 0;

        // KPI: Completed This Month
        $completedThisMonth = PortCall::where('status', 'completed')
            ->whereMonth('atd', $now->month)
            ->count();

        // Live Pending Billing Summary (Admins only)
        $pendingBilling = [
            'total_pending' => 0,
            'berthing_pending' => 0,
            'warehouse_pending' => 0,
            'assets_pending' => 0,
            'count' => 0
        ];

        // 1. Calculate berthing charges for all active vessels
        $activePortCallIds = PortCall::whereIn('status', ['anchored', 'alongside'])->pluck('id');
        $activePortCalls = PortCall::whereIn('id', $activePortCallIds)->with(['berth', 'vessel'])->get();
        
        foreach ($activePortCalls as $portCall) {
            if ($portCall->berth && $portCall->eta) {
                // Approximate calculation (Actual calculation happens in BillingService)
                $daysAlongside = max(1, now()->diffInDays($portCall->atb ?? $portCall->eta));
                $berthRate = $portCall->berth->rate_per_day ?? 500;
                $pendingBilling['berthing_pending'] += $daysAlongside * $berthRate;
                $pendingBilling['count']++;
            }
        }

        // 2. Warehouse Revenue Summary
        $billingService = new WarehouseBillingService();
        $warehouseSummary = $billingService->getOrganizationSummary();
        $pendingBilling['warehouse_pending'] = $warehouseSummary['total_charges'] ?? 0;
        
        // 3. Asset Rentals Revenue Summary
        $activeAssetBookings = \App\Models\AssetBooking::with('asset')
            ->where('status', 'active')
            ->get();
            
        foreach ($activeAssetBookings as $booking) {
            $hours = max(1, now()->diffInHours($booking->start_time));
            $pendingBilling['assets_pending'] += $hours * ($booking->asset->rate_per_hour ?? 0);
        }

        $pendingBilling['total_pending'] = $pendingBilling['berthing_pending'] + 
                                         $pendingBilling['warehouse_pending'] + 
                                         $pendingBilling['assets_pending'];

        // Unpaid Invoices Summary
        $unpaidInvoicesData = Invoice::where('status', '!=', 'paid')->get();
        $unpaidTotal = $unpaidInvoicesData->sum('total_amount');
        $unpaidCount = $unpaidInvoicesData->count();

        // Service Requests
        $activeServiceRequests = ServiceRequest::whereIn('status', ['pending', 'in_progress'])->count();

        // Recent Activity
        $recentActivity = PortCall::with(['vessel', 'agent', 'berth'])
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->get();

        // Top Agents
        $topAgents = Invoice::where('status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->selectRaw('organization_id, SUM(total_amount) as revenue')
            ->groupBy('organization_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->with('organization')
            ->get();

        return [
            'mode' => 'admin',
            'stats' => [
                'alongside' => $alongsideCount,
                'expected_arrivals' => $expectedArrivals,
                'occupancy_rate' => $occupancyRate,
                'completed_month' => $completedThisMonth,
            ],
            'pendingBilling' => $pendingBilling,
            'unpaidInvoices' => [
                'total' => $unpaidTotal,
                'count' => $unpaidCount
            ],
            'activeServiceRequests' => $activeServiceRequests,
            'warehouseSummary' => $warehouseSummary,
            'recentActivity' => $recentActivity,
            'topAgents' => $topAgents,
            'pendingRequests' => PortCall::where('status', 'requested')->with(['vessel', 'agent'])->get(),
            'activeBerths' => $activeBerths,
        ];
    }

    private function renderAgentDashboard($user, $now)
    {
        $orgId = $user->organization_id;

        // KPI: Active Vessels
        $activeVesselsCount = PortCall::where('agent_id', $orgId)
            ->whereIn('status', ['anchored', 'alongside'])
            ->count();
        
        // KPI: Pending Requests
        $pendingRequestsCount = PortCall::where('agent_id', $orgId)
            ->where('status', 'requested')
            ->count();

        // KPI: Unpaid Invoices
        $unpaidInvoicesCount = Invoice::where('organization_id', $orgId)
            ->where('status', '!=', 'paid')
            ->count();

        // Live Billing
        $billingService = new WarehouseBillingService();
        $warehouseBilling = $billingService->calculateLiveCharges($orgId);
        
        // Asset Billing
        $activeAssetBookings = \App\Models\AssetBooking::with('asset')
            ->where('organization_id', $orgId)
            ->where('status', 'active')
            ->get();
            
        $assetCharges = 0;
        foreach ($activeAssetBookings as $booking) {
            $hours = max(1, now()->diffInHours($booking->start_time));
            $assetCharges += $hours * ($booking->asset->rate_per_hour ?? 0);
        }
        
        $liveBilling = [
        'berthing_charges' => 0,
        'warehouse_charges' => $warehouseBilling['total_charges'] ?? 0,
        'assets_charges' => $assetCharges,
        'total_charges' => 0,
        'berthing_vessels' => 0
    ];

    $myActiveVessels = PortCall::where('agent_id', $orgId)
        ->whereIn('status', ['anchored', 'alongside'])
        ->with(['berth', 'vessel'])
        ->get();

    // Use BillingService for accurate real-time charges (same as Agent Portal)
    $billingService = new \App\Services\BillingService();
    foreach ($myActiveVessels as $vessel) {
        if ($vessel->berth && $vessel->eta) {
            // Generate/update invoice to get current charges
            $invoice = $billingService->generateInvoice($vessel);
            $liveBilling['berthing_charges'] += $invoice->total_amount;
            $liveBilling['berthing_vessels']++;
        }
    }
        $liveBilling['total_charges'] = $liveBilling['berthing_charges'] + 
                                      $liveBilling['warehouse_charges'] + 
                                      $liveBilling['assets_charges'];

        // Fleet Activity
        $recentActivity = PortCall::where('agent_id', $orgId)
            ->with(['vessel', 'berth'])
            ->orderBy('updated_at', 'desc')
            ->take(6)
            ->get();

        return [
            'mode' => 'agent',
            'stats' => [
                'active_vessels' => $activeVesselsCount,
                'pending_requests' => $pendingRequestsCount,
                'unpaid_invoices' => $unpaidInvoicesCount,
            ],
            'liveBilling' => $liveBilling,
            'recentActivity' => $recentActivity,
        ];
    }
}
