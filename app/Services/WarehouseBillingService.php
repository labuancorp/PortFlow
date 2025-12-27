<?php

namespace App\Services;

use App\Models\CargoItem;
use App\Models\Organization;
use App\Models\AssetBooking;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        
        // Fetch all rates indexed by zone_type
        $rates = DB::table('warehouse_billing_rates')
            ->where('is_active', true)
            ->get()
            ->keyBy('zone_type');
            
        // Default rate if zone not found
        $defaultRate = $rates->get('general') ?? (object)[
            'rate_per_m3_per_day' => 5.00,
            'dg_surcharge_percentage' => 50.00,
            'minimum_charge' => 50.00,
            'bulk_discount_threshold_m3' => 100.00,
            'bulk_discount_percentage' => 10.00,
            'penalty_after_days' => 30,
            'penalty_surcharge_percentage' => 20.00
        ];

        $totalCharges = 0;
        $itemsBreakdown = [];

        foreach ($items as $item) {
            $breakdown = $this->calculateItemCost($item, $rates, $defaultRate);
            $totalCharges += $breakdown['total'];
            $itemsBreakdown[] = $breakdown;
        }

        return [
            'total_charges' => round($totalCharges, 2),
            'items_count' => $items->count(),
            'items_breakdown' => $itemsBreakdown,
            'timestamp' => now()
        ];
    }

    private function calculateItemCost($item, $rates, $defaultRate)
    {
        $zoneType = $item->zone->type ?? 'general';
        $rate = $rates->get($zoneType) ?? $defaultRate;
        
        // If discharged, use stored duration, else current duration
        $endTime = $item->discharged_at ?? now();
        $daysStored = max(1, $endTime->diffInDays($item->created_at)); // Use entry_time if available
        $volume = $item->volume_m3 ?? 1;
        
        // 1. Base charge
        $dailyRate = $rate->rate_per_m3_per_day;
        
        // 2. Volume Discount (Applies to rate if item is large)
        $discountAmount = 0;
        if (isset($rate->bulk_discount_threshold_m3) && $volume >= $rate->bulk_discount_threshold_m3) {
            $discount = ($rate->bulk_discount_percentage / 100);
            $dailyRate = $dailyRate * (1 - $discount);
            $discountAmount = ($rate->rate_per_m3_per_day * $volume * $daysStored) * $discount;
        }

        $baseCharge = $volume * $dailyRate * $daysStored;
        
        // 3. DG surcharge
        $dgSurcharge = 0;
        if ($item->dg_class) {
            $dgSurcharge = $baseCharge * ($rate->dg_surcharge_percentage / 100);
        }
        
        // 4. Penalty (Long term storage)
        $penaltySurcharge = 0;
        if (isset($rate->penalty_after_days) && $daysStored > $rate->penalty_after_days) {
            // Apply penalty on the base charge
            $penaltySurcharge = $baseCharge * ($rate->penalty_surcharge_percentage / 100);
        }

        $itemTotal = max($rate->minimum_charge, $baseCharge + $dgSurcharge + $penaltySurcharge);

        return [
            'tracking_number' => $item->tracking_number,
            'description' => $item->description,
            'zone_type' => $zoneType,
            'volume_m3' => $volume,
            'days_stored' => $daysStored,
            'is_dg' => (bool)$item->dg_class,
            'base_charge' => $baseCharge,
            'dg_surcharge' => $dgSurcharge,
            'penalty_surcharge' => $penaltySurcharge,
            'discount_amount' => $discountAmount,
            'total' => $itemTotal,
            'agent' => $item->manifest->agent->name ?? 'Unknown'
        ];
    }
    
    /**
     * Bill a Cargo Item upon discharge
     */
    public function billCargoItem(CargoItem $item)
    {
         if ($item->status !== 'discharged') {
             // Logic to set as discharged if not already
             $item->update(['status' => 'discharged', 'discharged_at' => now()]);
         }

         // Fetch rates
         $rates = DB::table('warehouse_billing_rates')->where('is_active', true)->get()->keyBy('zone_type');
         $defaultRate = $rates->get('general') ?? (object)[
            'rate_per_m3_per_day' => 5.00,
            'dg_surcharge_percentage' => 50.00,
            'minimum_charge' => 50.00,
            'bulk_discount_threshold_m3' => 100.00,
            'bulk_discount_percentage' => 10.00,
            'penalty_after_days' => 30,
            'penalty_surcharge_percentage' => 20.00
        ];

         $costData = $this->calculateItemCost($item, $rates, $defaultRate);

         // Create/Find Invoice for this Agent
         $agentId = $item->manifest->agent_id;
         $invoice = Invoice::firstOrCreate(
            [
                'organization_id' => $agentId,
                'status' => 'draft', 
                // In real app, might group by month: 'period' => now()->format('Y-m')
            ],
            [
                'invoice_no' => 'INV-WHS-' . strtoupper(Str::random(6)),
                'issued_date' => now(),
                'due_date' => now()->addDays(30),
                'total_amount' => 0
            ]
         );

         // Add Item
         $desc = "Storage: {$item->description} ({$costData['volume_m3']}m3 x {$costData['days_stored']} days)";
         if ($costData['is_dg']) $desc .= " [DG]";
         if ($costData['penalty_surcharge'] > 0) $desc .= " [Late Penalty]";

         InvoiceItem::create([
             'invoice_id' => $invoice->id,
             'description' => $desc,
             'quantity' => 1,
             'unit_price' => $costData['total'],
             'total_price' => $costData['total']
         ]);

         // Update Total
         $invoice->update(['total_amount' => $invoice->invoiceItems()->sum('total_price')]);

         return $invoice;
    }

    /**
     * Create Invoice from Completed Asset Booking
     */
    public function createInvoiceFromAssetBooking(AssetBooking $booking)
    {
        if ($booking->status !== 'completed' || !$booking->end_time) {
            return null; // Can only bill completed bookings
        }
        
        if ($booking->total_cost > 0) {
            return null; 
        }

        // 1. Create Invoice
        $invoice = Invoice::create([
             'organization_id' => $booking->organization_id,
             'invoice_no' => 'INV-ASSET-' . strtoupper(Str::random(6)),
             'status' => 'issued',
             'issued_date' => now(),
             'due_date' => now()->addDays(30),
             'total_amount' => 0 // Will update
        ]);

        // 2. Calculate Duration
        $start = Carbon::parse($booking->start_time);
        $end = Carbon::parse($booking->end_time);
        
        $hours = max(1, $end->floatDiffInHours($start));
        $days = max(1, $end->floatDiffInDays($start));

        $cost = 0;
        $description = "";

        $hourlyCost = $hours * ($booking->asset->rate_per_hour ?? 0);
        $dailyCost = ceil($days) * ($booking->asset->rate_per_day ?? 0);

        if ($booking->asset->rate_per_day > 0 && ($dailyCost < $hourlyCost || $hourlyCost == 0)) {
            $cost = $dailyCost;
            $qty = ceil($days);
            $unit = 'days';
            $unitPrice = $booking->asset->rate_per_day;
            $description = "Asset Rental: {$booking->asset->name} ({$booking->asset->identifier}) - {$qty} Days";
        } else {
            $cost = $hourlyCost;
            $qty = round($hours, 2);
            $unit = 'hours';
            $unitPrice = $booking->asset->rate_per_hour;
            $description = "Asset Rental: {$booking->asset->name} ({$booking->asset->identifier}) - {$qty} Hours";
        }

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'description' => $description,
            'quantity' => $qty,
            'unit_price' => $unitPrice,
            'total_price' => $cost
        ]);

        $invoice->update(['total_amount' => $cost]);
        $booking->update(['total_cost' => $cost, 'status' => 'completed']);

        return $invoice;
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
