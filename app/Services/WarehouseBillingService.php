<?php

namespace App\Services;

use App\Models\CargoItem;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class WarehouseBillingService
{
    /**
     * Calculate live warehouse charges for an organization
     */
    public function calculateLiveCharges($organizationId = null)
    {
        $query = CargoItem::with(['manifest.agent', 'zone'])
            ->whereNotIn('status', ['discharged', 'completed']);

        if ($organizationId) {
            $query->whereHas('manifest', function($q) use ($organizationId) {
                $q->where('agent_id', $organizationId);
            });
        }

        $items = $query->get();
        
        $rate = DB::table('warehouse_billing_rates')
            ->where('is_active', true)
            ->first();

        if (!$rate) {
            $rate = (object)[
                'rate_per_m3_per_day' => 5.00,
                'dg_surcharge_percentage' => 50.00,
                'minimum_charge' => 50.00
            ];
        }

        $totalCharges = 0;
        $itemsBreakdown = [];

        foreach ($items as $item) {
            $daysStored = max(1, now()->diffInDays($item->created_at));
            $volume = $item->volume_m3 ?? 1;
            
            // Base charge
            $baseCharge = $volume * $rate->rate_per_m3_per_day * $daysStored;
            
            // DG surcharge
            $dgSurcharge = 0;
            if ($item->dg_class) {
                $dgSurcharge = $baseCharge * ($rate->dg_surcharge_percentage / 100);
            }
            
            $itemTotal = max($rate->minimum_charge, $baseCharge + $dgSurcharge);
            $totalCharges += $itemTotal;

            $itemsBreakdown[] = [
                'tracking_number' => $item->tracking_number,
                'description' => $item->description,
                'volume_m3' => $volume,
                'days_stored' => $daysStored,
                'is_dg' => (bool)$item->dg_class,
                'base_charge' => $baseCharge,
                'dg_surcharge' => $dgSurcharge,
                'total' => $itemTotal,
                'agent' => $item->manifest->agent->name ?? 'Unknown'
            ];
        }

        return [
            'total_charges' => round($totalCharges, 2),
            'items_count' => $items->count(),
            'items_breakdown' => $itemsBreakdown,
            'rate_info' => [
                'base_rate' => $rate->rate_per_m3_per_day,
                'dg_surcharge_pct' => $rate->dg_surcharge_percentage,
                'minimum_charge' => $rate->minimum_charge
            ]
        ];
    }

    /**
     * Get summary by organization
     */
    public function getOrganizationSummary()
    {
        $organizations = Organization::where('type', 'agent')
            ->where('warehouse_subscribed', true)
            ->get();

        $summary = [];

        foreach ($organizations as $org) {
            $charges = $this->calculateLiveCharges($org->id);
            $summary[] = [
                'organization_id' => $org->id,
                'organization_name' => $org->name,
                'total_charges' => $charges['total_charges'],
                'items_count' => $charges['items_count']
            ];
        }

        return $summary;
    }
}
