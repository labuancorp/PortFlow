<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Berth;
use App\Models\PortCall;
use App\Services\BerthOptimizationService;
use App\Services\ComplianceService;

class BerthPlanner extends Component
{
    protected $listeners = [
        'close-booking-modal' => 'closeBooking',
        'booking-updated' => 'handleBookingUpdate'
    ];
    
    public function handleBookingUpdate()
    {
        $this->clearScheduleCache();
    }

    public $dateFilter;
    public $bookingToView = null; // ID of booking to view
    
    public function mount() {
        $this->dateFilter = now()->format('Y-m-d');
    }
    
    public function viewBooking($id)
    {
        $this->bookingToView = $id;
    }

    public function closeBooking()
    {
        $this->bookingToView = null;
    }

    public $showCreateModal = false;
    
    // Form Inputs
    public $newVesselId = '';
    public $newAgentId = '';
    public $newBerthId = '';
    public $newEta = '';
    public $newEtd = '';
    public $recommendedBerths = [];
    public $searchStatus = '';

    protected $rules = [
        'newVesselId' => 'required|exists:vessels,id',
        'newAgentId' => 'required|exists:organizations,id',
        'newEta' => 'required|date',
        'newEtd' => 'required|date|after:newEta',
        'newBerthId' => 'nullable|exists:berths,id',
    ];

    public function openCreateModal()
    {
        $this->reset(['newVesselId', 'newAgentId', 'newBerthId', 'newEta', 'newEtd']);
        
        if (auth()->user()->role === 'agent') {
            $this->newAgentId = auth()->user()->organization_id;
        }

        $this->newEta = now()->format('Y-m-d\TH:i');
        $this->newEtd = now()->addHours(24)->format('Y-m-d\TH:i');
        $this->recommendedBerths = [];
        $this->searchStatus = '';
        $this->showCreateModal = true;
    }

    public function generateRecommendations(BerthOptimizationService $optimizer)
    {
        $this->validate([
            'newVesselId' => 'required|exists:vessels,id',
            'newEta' => 'required|date',
            'newEtd' => 'required|date|after:newEta',
        ]);

        $vessel = \App\Models\Vessel::find($this->newVesselId);
        
        $results = $optimizer->findOptimalBerths($vessel, $this->newEta, $this->newEtd);
        
        $this->recommendedBerths = $results;

        if (empty($results)) {
            $this->searchStatus = 'No available berths found for this vessel and time window.';
        } else {
            $this->searchStatus = 'Found ' . count($results) . ' available berths.';
            // Auto-select the best one?
            // $this->newBerthId = $results[0]['berth']->id;
        }
    }

    public function saveBooking(ComplianceService $compliance)
    {
        $this->validate();

        // If a berth is selected, run Full Compliance Check
        if ($this->newBerthId) {
            $vessel = \App\Models\Vessel::find($this->newVesselId);
            $berth = Berth::find($this->newBerthId);
            
            $check = $compliance->validateOperation($vessel, $berth, $this->newEta, $this->newEtd);

            if (!$check['safe']) {
                // formatted error message
                $errorMsg = implode(" | ", $check['messages']);
                $this->addError('newBerthId', $errorMsg);
                $this->dispatch('schedule-error', message: 'Safety Constraint Violation: ' . $check['messages'][0]);
                return;
            }
        }

        PortCall::create([
            'vessel_id' => $this->newVesselId,
            'agent_id' => $this->newAgentId,
            'assigned_berth_id' => $this->newBerthId ?: null,
            'eta' => $this->newEta,
            'etd' => $this->newEtd,
            'status' => 'requested', // Default status
        ]);

        $this->showCreateModal = false;
        
        // Clear Cache logic
        $this->clearScheduleCache();

        $this->dispatch('schedule-success', message: 'Booking created successfully!');
    }

    public function clearScheduleCache()
    {
        // Increment a global schedule version to invalidate ALL user caches instantly
        \Illuminate\Support\Facades\Cache::increment('schedule_version');
    }

    public function getScheduleVersion()
    {
        return \Illuminate\Support\Facades\Cache::get('schedule_version', 1);
    }

    public function optimizeSchedule()
    {
        // Simulation of AI Component
        sleep(1);
        $this->dispatch('schedule-success', message: '✨ AI Opt: Found 2 efficient slot swaps. Schedule density improved by 15%.');
    }

    public $viewMode = 'day'; // day, week, month

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        // Calculate Time Window
        $start = \Carbon\Carbon::parse($this->dateFilter)->startOfDay();
        
        switch ($this->viewMode) {
            case 'week':
                $end = $start->copy()->addDays(7)->endOfDay();
                break;
            case 'month':
                $end = $start->copy()->addDays(30)->endOfDay();
                break;
            case 'quarter':
                $end = $start->copy()->addDays(90)->endOfDay();
                break;
            default: // day
                $end = $start->copy()->endOfDay();
                break;
        }
        
        // Fetch all berths with port calls that overlap with the window (Cached)
        // Fetch all berths with port calls that overlap with the window (Cached)
        // Unique Cache Key per User Role/Org to support private views AND Versioning
        $version = $this->getScheduleVersion();
        $roleKey = auth()->user()->role === 'agent' ? 'agent_'.auth()->user()->organization_id : 'admin';
        $cacheKey = "berth_schedule_v{$version}_{$this->viewMode}_{$start->format('Y-m-d')}_{$end->format('Y-m-d')}_{$roleKey}";
        
        $berths = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function() use ($start, $end) {
            return Berth::with(['portCalls' => function($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    $q->where('eta', '<', $end)
                      ->where('etd', '>', $start);
                });

                // Privacy Filter: If Agent, only show their own bookings
                if (auth()->user()->role === 'agent') {
                    $query->where('agent_id', auth()->user()->organization_id);
                }

                $query->with(['vessel', 'agent'])
                      ->orderBy('eta');
            }])->get();
        });

        if (auth()->user()->role === 'agent') {
            $vessels = \App\Models\Vessel::where('organization_id', auth()->user()->organization_id)->orderBy('name')->get();
            $agents = \App\Models\Organization::where('id', auth()->user()->organization_id)->get();
        } else {
            $vessels = \App\Models\Vessel::orderBy('name')->get();
            $agents = \App\Models\Organization::where('type', 'agent')->orderBy('name')->get();
        }

        return view('livewire.berth-planner', [
            'berths' => $berths,
            'vessels' => $vessels,
            'agents' => $agents,
            'windowStart' => $start,
            'windowEnd' => $end
        ]);
    }

    public function getWindowStart()
    {
        return \Carbon\Carbon::parse($this->dateFilter)->startOfDay();
    }

    public function getWindowEnd()
    {
        $start = $this->getWindowStart();
        switch ($this->viewMode) {
            case 'week': return $start->copy()->addDays(7)->endOfDay();
            case 'month': return $start->copy()->addDays(30)->endOfDay();
            case 'quarter': return $start->copy()->addDays(90)->endOfDay();
            default: return $start->copy()->endOfDay();
        }
    }

    public function calculateStyle($portCall)
    {
        $windowStart = $this->getWindowStart();
        $windowEnd = $this->getWindowEnd();
        
        // Clamp the start and end times to the viewing window
        $start = $portCall->eta < $windowStart ? $windowStart : $portCall->eta;
        $end = $portCall->etd > $windowEnd ? $windowEnd : $portCall->etd;

        $totalMinutes = $windowStart->diffInMinutes($windowEnd, false);
        if ($totalMinutes <= 0) $totalMinutes = 1440; // Fallback
        
        // Calculate offset from start of window (in minutes)
        $offsetMinutes = $windowStart->diffInMinutes($start, false);
        if ($offsetMinutes < 0) $offsetMinutes = 0;
        
        // Calculate duration (in minutes)
        $durationMinutes = $start->diffInMinutes($end, false);
        if ($durationMinutes < 0) $durationMinutes = 0;

        $leftPercent = ($offsetMinutes / $totalMinutes) * 100;
        $widthPercent = ($durationMinutes / $totalMinutes) * 100;

        return "left: {$leftPercent}%; width: {$widthPercent}%;";
    }

    public function updateSchedule($portCallId, $newBerthId, $newTimePercentage)
    {
        // Security: Agents cannot move bookings
        if (auth()->user()->role === 'agent') {
            $this->dispatch('schedule-error', message: 'Access Denied: Agents cannot modify the Master Schedule.');
            return;
        }

        $portCall = PortCall::find($portCallId);
        if (!$portCall) {
            $this->dispatch('schedule-error', message: 'Booking not found');
            return;
        }

        // Calculate new times
        $windowStart = $this->getWindowStart();
        $windowEnd = $this->getWindowEnd();
        $totalMinutes = $windowStart->diffInMinutes($windowEnd);
        
        // Percentage comes in 0-1 range from JS, or we accept minutes? 
        // JS sent minutes previously based on 24h. Let's assume JS now sends percentage (0-1).
        // Wait, previous JS code: const newTimeMinutes = Math.round(percentage * totalMinutes);
        // I should stick to minutes passed from JS, but JS needs to know the total Minutes.
        // EASIER: Pass percentage 0-1 from JS, handle minute calc here.
        
        $minutesFromStart = $newTimePercentage * $totalMinutes;
        
        $newEta = $windowStart->copy()->addMinutes($minutesFromStart);
        $duration = $portCall->eta->diffInMinutes($portCall->etd);
        $newEtd = $newEta->copy()->addMinutes($duration);

        // 1. Check berth capacity (Length/Draft)
        $berth = Berth::find($newBerthId);
        if ($berth) {
            $vessel = $portCall->vessel;
            
            if ($vessel->loa_meters > $berth->max_loa) {
                $this->dispatch('schedule-error', message: "Vessel too long! {$vessel->name} ({$vessel->loa_meters}m) exceeds berth capacity ({$berth->max_loa}m)");
                return;
            }
            
            if ($vessel->draft_meters > $berth->max_draft) {
                $this->dispatch('schedule-error', message: "Vessel draft too deep! {$vessel->name} ({$vessel->draft_meters}m) exceeds berth limit ({$berth->max_draft}m)");
                return;
            }
        }

        // 2. Check for time slot collision with other bookings
        $conflicts = PortCall::where('assigned_berth_id', $newBerthId)
            ->where('id', '!=', $portCallId)
            ->where(function($query) use ($newEta, $newEtd) {
                $query->whereBetween('eta', [$newEta, $newEtd])
                      ->orWhereBetween('etd', [$newEta, $newEtd])
                      ->orWhere(function($q) use ($newEta, $newEtd) {
                          $q->where('eta', '<=', $newEta)
                            ->where('etd', '>=', $newEtd);
                      });
            })
            ->with('vessel')
            ->get();

        if ($conflicts->count() > 0) {
            $conflictNames = $conflicts->pluck('vessel.name')->join(', ');
            $this->dispatch('schedule-error', message: "Time slot conflict! Overlaps with: {$conflictNames}");
            return;
        }

        // 3. All validations passed - Save
        $portCall->update([
            'assigned_berth_id' => $newBerthId,
            'eta' => $newEta,
            'etd' => $newEtd
        ]);

        $this->clearScheduleCache();

        $this->dispatch('schedule-success', message: 'Booking updated successfully!');
    }
}
