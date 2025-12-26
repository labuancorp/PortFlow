<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use Livewire\Component;
use Livewire\WithPagination;

class AuditTrail extends Component
{
    use WithPagination;

    public $search = '';
    public $moduleFilter = '';
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedModuleFilter()
    {
        $this->resetPage();
    }

    public function getModulesProperty()
    {
        return AuditLog::select('module')->distinct()->pluck('module');
    }
    
    protected function getFilteredQuery()
    {
        return AuditLog::with('user')
            ->when($this->search, function ($query) {
                $query->where('details', 'like', '%' . $this->search . '%')
                    ->orWhere('action', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->moduleFilter, function ($query) {
                $query->where('module', $this->moduleFilter);
            })
            ->latest();
    }

    public function export()
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Timestamp', 'User', 'Role', 'IP Address', 'Module', 'Action', 'Details']);

            $this->getFilteredQuery()->chunk(200, function ($logs) use ($handle) {
                foreach ($logs as $log) {
                    fputcsv($handle, [
                        $log->created_at->format('Y-m-d H:i:s'),
                        $log->user->name ?? 'System',
                        $log->user->role ?? 'system',
                        $log->ip_address,
                        $log->module,
                        $log->action,
                        $log->details
                    ]);
                }
            });

            fclose($handle);
        }, 'audit_logs_' . now()->format('Y_m_d_Hi') . '.csv');
    }

    public function render()
    {
        $logs = $this->getFilteredQuery()->paginate(15);

        return view('livewire.admin.audit-trail', [
            'logs' => $logs
        ]);
    }
}
