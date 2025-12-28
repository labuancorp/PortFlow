<?php

namespace App\Services;

use App\Models\PortCall;
use App\Models\InventoryItem;
use Carbon\Carbon;

class PredictiveAnalyticsService
{
    public function getDemandForecast()
    {
        $start = now();
        $end = now()->addDays(7);
        
        $incomingCalls = PortCall::with('vessel')
            ->whereBetween('eta', [$start, $end])
            ->get();
            
        $insights = [];
        $assetDemand = [
            'crane' => 0,
            'forklift' => 0,
            'water' => 0,
        ];

        // 1. Check Inventory Health
        $lowStockItems = InventoryItem::whereColumn('current_stock', '<=', 'min_threshold')->get();
        foreach ($lowStockItems as $item) {
            $insights[] = [
                'type' => 'critical',
                'message' => "Low Stock Alert: {$item->name} is at {$item->current_stock} {$item->unit} (Threshold: {$item->min_threshold}). Reorder immediately."
            ];
        }

        // 2. Forecast Demand based on incoming vessels
        foreach ($incomingCalls as $call) {
            $vesselType = strtolower($call->vessel->type ?? 'generic');
            
            // Heuristic Rule Engine
            if (str_contains($vesselType, 'offshore') || str_contains($vesselType, 'supply')) {
                $assetDemand['crane'] += 2; // High probability of needing 2 cranes
                $assetDemand['forklift'] += 4;
            } elseif (str_contains($vesselType, 'tanker')) {
                $assetDemand['water'] += 100; // MT
            } else {
                $assetDemand['forklift'] += 1;
            }
        }

        // Generate Insights from Demand
        if ($assetDemand['crane'] > 5) {
            $insights[] = [
                'type' => 'critical',
                'message' => "High Crane Demand Forecast: {$assetDemand['crane']} units needed next week. Ensure maintenance is cleared."
            ];
        }

        if ($incomingCalls->count() > 8) {
             $insights[] = [
                'type' => 'warning',
                'message' => "Congestion Alert: {$incomingCalls->count()} vessels arriving. Yard density projected to exceed 85%."
            ];
        }

        if (empty($insights)) {
             $insights[] = [
                'type' => 'info',
                'message' => "Normal operations projected. Resource availability is sufficient for incoming traffic."
            ];
        }

        return $insights;
    }
}
