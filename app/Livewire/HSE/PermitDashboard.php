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

    public function approve($id)
    {
        $permit = WorkPermit::find($id);
        if ($permit && $permit->status === 'requested') {
            $permit->update(['status' => 'approved', 'approved_by' => auth()->id()]);
            AuditService::log('Update', 'HSE', $permit->id, "Approved Permit {$permit->control_no}");
            $this->dispatch('notify', message: 'Permit has been approved.', type: 'success');
        }
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
