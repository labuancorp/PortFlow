<?php

namespace App\Livewire\Analytics;

use Livewire\Component;
use App\Models\PortCall;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $dateRange = 'month'; // month, quarter, year

    public function render()
    {
        $startDate = $this->getStartDate();
        
        // 1. Total Port Calls
        $totalCalls = PortCall::where('eta', '>=', $startDate)->count();
        $previousCalls = PortCall::whereBetween('eta', [$startDate->copy()->subMonth(), $startDate])->count();
        $callsGrowth = $previousCalls > 0 ? (($totalCalls - $previousCalls) / $previousCalls) * 100 : 0;

        // 2. Revenue (Assuming total_amount exists on invoices)
        $revenue = Invoice::where('created_at', '>=', $startDate)->sum('total_amount');
        
        // 3. Average Turnaround Time (in hours) - Completed calls only
        $completedCalls = PortCall::where('status', 'completed')
            ->where('eta', '>=', $startDate)
            ->whereNotNull('atb')
            ->whereNotNull('atd')
            ->get();
            
        $avgTurnaround = $completedCalls->count() > 0 
            ? $completedCalls->average(fn($call) => $call->atb->diffInHours($call->atd))
            : 0;

        // 4. Berth Utilization (Simple count per berth)
        $berthVisualization = PortCall::select('assigned_berth_id', DB::raw('count(*) as total'))
            ->where('eta', '>=', $startDate)
            ->whereNotNull('assigned_berth_id')
            ->groupBy('assigned_berth_id')
            ->with('berth')
            ->get();

        return view('livewire.analytics.dashboard', [
            'totalCalls' => $totalCalls,
            'callsGrowth' => $callsGrowth,
            'revenue' => $revenue,
            'avgTurnaround' => round($avgTurnaround, 1),
            'berthStats' => $berthVisualization
        ])->layout('components.layouts.app'); // Assuming admin layout
    }

    private function getStartDate()
    {
        return match($this->dateRange) {
            'quarter' => Carbon::now()->subMonths(3),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth(),
        };
    }
}
