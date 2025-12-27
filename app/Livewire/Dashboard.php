<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Berth;
use App\Models\Vessel;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $mode = 'admin'; // or 'agent'
    public $showUnpaidModal = false;
    public $unpaidInvoicesList = [];

    public function openUnpaidModal()
    {
        $this->unpaidInvoicesList = \App\Models\Invoice::where('status', '!=', 'paid')
            ->with('organization')
            ->orderBy('created_at', 'desc')
            ->get();
        $this->showUnpaidModal = true;
    }

    public function closeModal()
    {
        $this->showUnpaidModal = false;
    }
    // Modal States
    public $showReviewModal = false;
    public $selectedBooking = null;
    public $selectedBerthId = null;
    public $reviewNote = '';

    // Alerts State
    public $showAlertsModal = false;
    public $activeAlerts = [];

    // Pilot Request State
    public $pilotRequested = false;
    public $pilotStatus = 'Idle';
    public $pilotProgress = 0;

    public function mount()
    {
        $this->pilotRequested = session()->get('pilot_requested', false);
        $this->pilotStatus = session()->get('pilot_status', 'Idle');
        $this->pilotProgress = session()->get('pilot_progress', 0);
        
        // Mock some alerts for the premium feel
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

    public function render()
    {
        $now = Carbon::now();
        $user = auth()->user();

        if ($user->role === 'agent') {
            return $this->renderAgentDashboard($user, $now);
        }

        return $this->renderAdminDashboard($now);
    }

    private function renderAgentDashboard($user, $now)
    {
        $orgId = $user->organization_id;

        // KPI: My Active Vessels
        $myActiveVessels = PortCall::where('agent_id', $orgId)
            ->whereIn('status', ['anchored', 'alongside'])
            ->count();
        
        // KPI: Pending Requests
        $myPendingRequests = PortCall::where('agent_id', $orgId)
            ->where('status', 'requested')
            ->count();

        // KPI: Invoices Due
        $unpaidInvoices = \App\Models\Invoice::where('organization_id', $orgId)
            ->where('status', '!=', 'paid')
            ->count();

        // Live Billing Calculation
        $liveBilling = [
            'berthing_charges' => 0,
            'warehouse_charges' => 0,
            'total_charges' => 0,
            'berthing_vessels' => 0,
            'warehouse_items' => 0
        ];

        // Calculate Berthing Charges (Active Vessels)
        $activePortCalls = PortCall::where('agent_id', $orgId)
            ->whereIn('status', ['anchored', 'alongside'])
            ->with('berth')
            ->get();

        foreach ($activePortCalls as $portCall) {
            if ($portCall->berth && $portCall->eta) {
                $daysAlongside = max(1, now()->diffInDays($portCall->eta));
                $berthRate = $portCall->berth->rate_per_day ?? 500; // Default RM 500/day
                $liveBilling['berthing_charges'] += $daysAlongside * $berthRate;
                $liveBilling['berthing_vessels']++;
            }
        }

        // Warehouse Billing (if subscribed)
        $warehouseBilling = null;
        if ($user->organization->warehouse_subscribed ?? false) {
            $billingService = new \App\Services\WarehouseBillingService();
            $warehouseBilling = $billingService->calculateLiveCharges($orgId);
            $liveBilling['warehouse_charges'] = $warehouseBilling['total_charges'];
            $liveBilling['warehouse_items'] = $warehouseBilling['items_count'];
        }

        $liveBilling['total_charges'] = $liveBilling['berthing_charges'] + $liveBilling['warehouse_charges'];

        // Recent Activity filter
        $recentActivity = PortCall::where('agent_id', $orgId)
            ->with(['vessel', 'berth'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'mode' => 'agent',
            'stats' => [
                'active_vessels' => $myActiveVessels,
                'pending_requests' => $myPendingRequests,
                'unpaid_invoices' => $unpaidInvoices,
            ],
            'liveBilling' => $liveBilling,
            'warehouseBilling' => $warehouseBilling,
            'recentActivity' => $recentActivity,
            'now' => $now
        ]);
    }

    private function renderAdminDashboard($now)
    {
        // KPI: Vessels Alongside
        $alongsideCount = PortCall::where('status', 'alongside')->count();

        // KPI: Expected Arrivals (Next 24h)
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

        // Live Pending Billing Summary
        $pendingBilling = [
            'total_pending' => 0,
            'berthing_pending' => 0,
            'warehouse_pending' => 0,
            'count' => 0
        ];

        // Calculate berthing charges for all active vessels
        $activePortCalls = PortCall::whereIn('status', ['anchored', 'alongside'])
            ->with('berth')
            ->get();

        foreach ($activePortCalls as $portCall) {
            if ($portCall->berth && $portCall->eta) {
                $daysAlongside = max(1, now()->diffInDays($portCall->eta));
                $berthRate = $portCall->berth->rate_per_day ?? 500;
                $pendingBilling['berthing_pending'] += $daysAlongside * $berthRate;
                $pendingBilling['count']++;
            }
        }

        // Calculate warehouse charges for all subscribed organizations
        $billingService = new \App\Services\WarehouseBillingService();
        $warehouseSummary = $billingService->getOrganizationSummary();
        $pendingBilling['warehouse_pending'] = $warehouseSummary['total_charges'] ?? 0;
        
        $pendingBilling['total_pending'] = $pendingBilling['berthing_pending'] + $pendingBilling['warehouse_pending'];

        // Unpaid Invoices Summary
        $unpaidInvoices = \App\Models\Invoice::where('status', '!=', 'paid')->get();
        $unpaidTotal = $unpaidInvoices->sum('total_amount');
        $unpaidCount = $unpaidInvoices->count();

        // Active Service Requests
        $activeServiceRequests = \App\Models\ServiceRequest::whereIn('status', ['pending', 'in_progress'])->count();

        // Recent Activity
        $recentActivity = PortCall::with(['vessel', 'agent', 'berth'])
            ->orderBy('updated_at', 'desc')
            ->take(8)
            ->get();

        // Top Agents by Revenue (This Month)
        $topAgents = \App\Models\Invoice::where('status', 'paid')
            ->whereMonth('created_at', $now->month)
            ->selectRaw('organization_id, SUM(total_amount) as revenue')
            ->groupBy('organization_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->with('organization')
            ->get();

        return view('livewire.dashboard', [
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
            'now' => $now
        ]);
    }

    // 1. Review Booking Flow
    public function openReviewModal($id)
    {
        $this->selectedBooking = PortCall::with(['vessel', 'agent'])->findOrFail($id);
        $this->selectedBerthId = null;
        $this->showReviewModal = true;
    }

    public function approveBooking()
    {
        $this->validate([
            'selectedBerthId' => 'required|exists:berths,id'
        ], [
            'selectedBerthId.required' => 'Please select a berth to assign.'
        ]);

        $this->selectedBooking->update([
            'status' => 'approved',
            'assigned_berth_id' => $this->selectedBerthId
        ]);

        $this->showReviewModal = false;
        session()->flash('success', "Booking for {$this->selectedBooking->vessel->name} APPROVED and assigned to " . Berth::find($this->selectedBerthId)->name);
    }

    public function rejectBooking()
    {
        if ($this->selectedBooking) {
            $this->selectedBooking->update(['status' => 'cancelled']);
            session()->flash('success', "Booking for {$this->selectedBooking->vessel->name} REJECTED.");
        }
        $this->showReviewModal = false;
    }

    // 2. Alerts Flow
    public function toggleAlertsModal()
    {
        $this->showAlertsModal = !$this->showAlertsModal;
    }

    public function dismissAlert($id)
    {
        $this->activeAlerts = array_values(array_filter($this->activeAlerts, fn($a) => $a['id'] != $id));
    }

    // 3. Pilotage Flow (Fancy Progress Simulation)
    public function requestPilot()
    {
        $this->pilotRequested = true;
        $this->pilotStatus = 'Dispatched';
        $this->pilotProgress = 10;
        
        session()->put('pilot_requested', true);
        session()->put('pilot_status', 'Dispatched');
        session()->put('pilot_progress', 10);
        
        session()->flash('success', "Pilot request dispatched to Marine Dept. 'Alpha 1' is en route.");
    }

    public function advancePilotSimulation()
    {
        if (!$this->pilotRequested) return;

        if ($this->pilotProgress < 100) {
            $this->pilotProgress += 20;
            if ($this->pilotProgress >= 100) {
                $this->pilotStatus = 'Alongside';
                $this->pilotProgress = 100;
            } elseif ($this->pilotProgress >= 60) {
                $this->pilotStatus = 'Entering Basins';
            } elseif ($this->pilotProgress >= 40) {
                $this->pilotStatus = 'En Route';
            }
            
            session()->put('pilot_status', $this->pilotStatus);
            session()->put('pilot_progress', $this->pilotProgress);
        } else {
            // Reset for demo purposes
            $this->pilotRequested = false;
            $this->pilotStatus = 'Idle';
            $this->pilotProgress = 0;
            session()->forget(['pilot_requested', 'pilot_status', 'pilot_progress']);
        }
    }

    // 4. Incident & Export
    public function logIncident()
    {
        \App\Services\AuditService::log('create', 'incident', null, 'Manual incident log triggered from dashboard.');
        session()->flash('success', "Security Incident Logged. PFSO notified and CCTV tagged.");
    }

    public function exportReport()
    {
        return response()->streamDownload(function () {
            echo "Timestamp,Event,Agent,Details\r\n";
            echo now()->toDateTimeString() . ",Report Export,Control Room,Daily Operations Snapshot\r\n";
        }, 'daily_operations_' . date('Ymd_His') . '.csv');
    }
}
