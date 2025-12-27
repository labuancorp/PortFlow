<?php

namespace App\Livewire\Analytics;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\PortCall;
use App\Models\WorkPermit;
use App\Models\WarehouseZone;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Revenue Metrics
        $totalRevenue = Invoice::where('status', '!=', 'draft')->sum('total_amount');
        $thisMonthRevenue = Invoice::where('status', '!=', 'draft')
            ->whereMonth('issued_date', now()->month)
            ->sum('total_amount');
            
        // 2. Operational Metrics
        $activeVessels = PortCall::where('status', 'alongside')->count();
        $yardUtilization = WarehouseZone::avg('current_utilization_m3') ?? 0; // Simple average for demo
        
        // 3. Safety Metrics
        $activePermits = WorkPermit::where('status', 'active')->count();

        // 4. Chart Data Preparation (Last 6 Months)
        $months = collect([]);
        $revenueData = collect([]);
        $vesselData = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            $months->push($monthName);

            // Revenue per month
            $rev = Invoice::where('status', '!=', 'draft')
                ->whereMonth('issued_date', $date->month)
                ->whereYear('issued_date', $date->year)
                ->sum('total_amount');
            $revenueData->push($rev);

            // Vessels per month
            $vessels = PortCall::whereMonth('arrival_time', $date->month)
                ->whereYear('arrival_time', $date->year)
                ->count();
            $vesselData->push($vessels);
        }

        return view('livewire.analytics.dashboard', [
            'totalRevenue' => $totalRevenue,
            'thisMonthRevenue' => $thisMonthRevenue,
            'activeVessels' => $activeVessels,
            'yardUtilization' => $yardUtilization,
            'activePermits' => $activePermits,
            'chartLabels' => $months,
            'revenueData' => $revenueData,
            'vesselData' => $vesselData
        ]);
    }
}
