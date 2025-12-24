<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Berth;
use App\Models\PortCall;

class BerthPlanner extends Component
{
    protected $listeners = ['close-booking-modal' => 'closeBooking'];

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
        $this->newEta = now()->format('Y-m-d\TH:i');
        $this->newEtd = now()->addHours(24)->format('Y-m-d\TH:i');
        $this->showCreateModal = true;
    }

    public function saveBooking()
    {
        $this->validate();

        PortCall::create([
            'vessel_id' => $this->newVesselId,
            'agent_id' => $this->newAgentId,
            'assigned_berth_id' => $this->newBerthId ?: null,
            'eta' => $this->newEta,
            'etd' => $this->newEtd,
            'status' => 'requested', // Default status
        ]);

        $this->showCreateModal = false;
        $this->dispatch('schedule-success', message: 'Booking created successfully!');
    }

    public function render()
    {
        $dayStart = \Carbon\Carbon::parse($this->dateFilter)->startOfDay();
        $dayEnd = \Carbon\Carbon::parse($this->dateFilter)->endOfDay();
        
        // Fetch all berths with port calls that overlap with the current day
        $berths = Berth::with(['portCalls' => function($query) use ($dayStart, $dayEnd) {
            $query->where(function($q) use ($dayStart, $dayEnd) {
                // Show bookings that overlap with the viewing day
                $q->where('eta', '<=', $dayEnd)
                  ->where('etd', '>=', $dayStart);
            })
            ->with(['vessel', 'agent'])
            ->orderBy('eta');
        }])->get();

        return view('livewire.berth-planner', [
            'berths' => $berths,
            'vessels' => \App\Models\Vessel::orderBy('name')->get(),
            'agents' => \App\Models\Organization::where('type', 'agent')->orderBy('name')->get(),
        ]);
    }

    public function calculateStyle($portCall)
    {
        $dayStart = \Carbon\Carbon::parse($this->dateFilter)->startOfDay();
        $dayEnd = \Carbon\Carbon::parse($this->dateFilter)->endOfDay();
        
        // Clamp the start and end times to the viewing window
        $start = $portCall->eta < $dayStart ? $dayStart : $portCall->eta;
        $end = $portCall->etd > $dayEnd ? $dayEnd : $portCall->etd;

        $totalMinutes = 24 * 60; // 1440 minutes in a day
        
        // Calculate offset from start of day (in minutes)
        $offsetMinutes = $dayStart->diffInMinutes($start, false);
        if ($offsetMinutes < 0) $offsetMinutes = 0;
        
        // Calculate duration (in minutes)
        $durationMinutes = $start->diffInMinutes($end, false);
        if ($durationMinutes < 0) $durationMinutes = 0;

        $leftPercent = ($offsetMinutes / $totalMinutes) * 100;
        $widthPercent = ($durationMinutes / $totalMinutes) * 100;

        return "left: {$leftPercent}%; width: {$widthPercent}%;";
    }

    public function updateSchedule($portCallId, $newBerthId, $newTimeMinutes)
    {
        $portCall = PortCall::find($portCallId);
        if (!$portCall) {
            $this->dispatch('schedule-error', message: 'Booking not found');
            return;
        }

        // Calculate new times
        $dayStart = \Carbon\Carbon::parse($this->dateFilter)->startOfDay();
        $newEta = $dayStart->copy()->addMinutes($newTimeMinutes);
        
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

        $this->dispatch('schedule-success', message: 'Booking updated successfully!');
    }
}
