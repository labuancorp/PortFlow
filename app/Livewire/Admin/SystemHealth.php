<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AuditLog;

class SystemHealth extends Component
{
    public function render()
    {
        // 1. Database Size (Simulated or raw query)
        $dbSize = "15 MB"; // SQLite file size approx

        // 2. Error Rates (from logs)
        $errorCount = 0; // Mock

        // 3. User Stats
        $activeUsers = User::count();

        // 4. Audit Trail Preview
        $recentLogs = AuditLog::with('user')->latest()->take(10)->get();

        return view('livewire.admin.system-health', [
            'dbSize' => $dbSize,
            'errorCount' => $errorCount,
            'activeUsers' => $activeUsers,
            'recentLogs' => $recentLogs
        ])->layout('components.layouts.app');
    }
}
