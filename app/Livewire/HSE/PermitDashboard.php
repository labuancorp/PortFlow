<?php

namespace App\Livewire\HSE;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WorkPermit;
use App\Services\AuditService;

class PermitDashboard extends Component
{
    use WithPagination;

    public $filterStatus = 'all';
    
    public function createTestConflict() 
    {
        WorkPermit::create([
             'control_no' => 'PTW-TEST-' . rand(100,999),
             'type' => 'hot_work',
             'location' => 'Zone A3 - Emergency Welding (DEMO)',
             'applicant_name' => 'Contractor X',
             'valid_from' => now(),
             'valid_to' => now()->addHours(2),
             'status' => 'requested',
             'description' => 'Urgent welding near fuel tanks (Simulated Risk)'
        ]);
        $this->dispatch('notify', message: 'Simulated High-Risk Permit Created.', type: 'info');
    }

    public function approve($id)
    {
        $permit = WorkPermit::find($id);
        if (!$permit) return;

        // 1. Safety Conflict Check (Phase 5 Feature)
        if ($this->hasSafetyConflict($permit)) {
            $this->dispatch('notify', message: 'CRITICAL SAFETY ALERT: Use of ignition source in DG Zone detected. Approval blocked.', type: 'error');
            return;
        }

        if ($permit->status === 'requested') {
            $permit->update(['status' => 'approved', 'approved_by' => auth()->id()]);
            AuditService::log('Update', 'HSE', $permit->id, "Approved Permit {$permit->control_no}");
            $this->dispatch('notify', message: 'Permit has been approved.', type: 'success');
        }
    }

    protected function hasSafetyConflict(WorkPermit $permit)
    {
        // Only check Hot Work against DG Zones for now
        if ($permit->type !== 'hot_work') return false;

        // Get all DG Zones
        $dgZones = \App\Models\WarehouseZone::where('is_dg_allowed', true)->pluck('code')->toArray();
        // e.g. ['A3', 'B2']

        foreach ($dgZones as $zoneCode) {
            // Check if permit location string "Zone A3 - Tank Inspection" contains "A3"
            if (str_contains($permit->location, $zoneCode)) {
                return true; 
            }
        }

        return false;
    }

    public function reject($id)
    {
        $permit = WorkPermit::find($id);
        if ($permit && $permit->status === 'requested') {
            $permit->update(['status' => 'rejected', 'approved_by' => auth()->id()]);
            AuditService::log('Update', 'HSE', $permit->id, "Rejected Permit {$permit->control_no}");
            $this->dispatch('notify', message: 'Permit has been rejected.', type: 'success');
        }
    }

    public function close($id)
    {
        $permit = WorkPermit::find($id);
        if ($permit && in_array($permit->status, ['approved', 'active'])) {
            $permit->update(['status' => 'closed']);
            AuditService::log('Update', 'HSE', $permit->id, "Closed Permit {$permit->control_no}");
            $this->dispatch('notify', message: 'Permit work completed and closed.', type: 'success');
        }
    }

    public function render()
    {
        $permits = WorkPermit::query()
            ->when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        return view('livewire.h-s-e.permit-dashboard', [
            'permits' => $permits
        ]);
    }
}
