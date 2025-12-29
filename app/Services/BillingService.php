<?php

namespace App\Services;

use App\Models\PortCall;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BillingService
{
    // Simplified Tariff Rates (In reality this would be in the database)
    const RATES = [
        'dockage_per_meter_hour' => 1.50, // RM 1.50 per meter of LOA per hour
        'wharfage_fixed' => 500.00,       // Fixed fee for docking
        'line_handling' => 250.00,        // Mooring gang fee (one-time)
        'fuel_per_liter' => 1.20,         // RM 1.20 per liter
        'water_per_mt' => 5.00,           // RM 5.00 per MT
    ];


    /**
     * Calculate and Generate/Update Invoice for a Port Call
     */
    public function generateInvoice(PortCall $portCall)
    {
        $portCall->loadMissing([
            'vessel', 
            'berth',
            'invoice', 
            'serviceRequests.portCall',
            'pilotageRequests.pilot',
            'towageRequests.tugboat'
        ]);
        // 1. Ensure Invoice Exists
        $invoice = $portCall->invoice ?? Invoice::create([
            'port_call_id' => $portCall->id,
            'organization_id' => $portCall->agent_id, // Bill to Agent for now
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'status' => 'draft',
            'issued_date' => now(),
            'due_date' => now()->addDays(30),
        ]);

        // IMPORTANT: Don't recalculate paid or issued invoices
        if ($invoice->exists && in_array($invoice->status, ['paid', 'issued'])) {
            // Invoice is already finalized, return as-is without recalculation
            return $invoice;
        }

        // 2. Clear existing items (for recalculation - only for draft invoices)
        $invoice->invoiceItems()->delete();

        $totalAmount = 0;

        // 3. Calculate Dockage (Time-Based)
        // If ATB (Actual Time Berthing) is set, we start billing
        if ($portCall->atb) {
            $start = $portCall->atb;
            // End is either ATD (Actual Time Departure) or NOW (if still alongside)
            $end = $portCall->atd ?? now();
            
            // Calculate duration in hours (rounded up)
            $hours = max(1, $start->diffInHours($end)); // Minimum 1 hour
            
            // Calculate Item Cost
            // Cost = Rate * LOA * Hours
            $loa = $portCall->vessel->loa_meters;
            $rate = self::RATES['dockage_per_meter_hour'];
            $dockageCost = $rate * $loa * $hours;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => "Dockage Fees ({$loa}m x {$hours} hrs @ RM {$rate}/m/hr)",
                'quantity' => $hours,
                'unit_price' => $rate * $loa,
                'total_price' => $dockageCost
            ]);

            $totalAmount += $dockageCost;

            // 4. Fixed Charges
            // Wharfage (Apply if ATB exists)
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => "Wharfage Fee (Fixed)",
                'quantity' => 1,
                'unit_price' => self::RATES['wharfage_fixed'],
                'total_price' => self::RATES['wharfage_fixed']
            ]);
            $totalAmount += self::RATES['wharfage_fixed'];

            // Line Handling (Apply if ATB exists)
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'description' => "Line Handling Services",
                'quantity' => 1,
                'unit_price' => self::RATES['line_handling'],
                'total_price' => self::RATES['line_handling']
            ]);
            $totalAmount += self::RATES['line_handling'];
            $totalAmount += self::RATES['line_handling'];
        }

        // 5. Add Service Requests (Fuel/Water)
        $services = $portCall->serviceRequests;
        if ($services) {
            foreach ($services as $service) {
                if ($service->status === 'delivered') {
                    $unitPrice = 0;
                    if ($service->service_type === 'fuel') {
                        $unitPrice = self::RATES['fuel_per_liter'];
                    } elseif ($service->service_type === 'water') {
                        $unitPrice = self::RATES['water_per_mt'];
                    }

                    $cost = $service->quantity * $unitPrice;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => "Supply: " . ucfirst($service->service_type) . " ({$service->quantity} {$service->unit})",
                        'quantity' => $service->quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $cost
                    ]);

                    $totalAmount += $cost;
                }
            }
        }

        // 6. Add Maritime Services (Phase 6: Pilotage & Towage)
        // Load pilotage requests
        $pilotageRequests = $portCall->pilotageRequests()->where('status', 'completed')->with('pilot')->get();
        foreach ($pilotageRequests as $pilotage) {
            if ($pilotage->calculated_fee > 0) {
                $pilotName = $pilotage->pilot ? $pilotage->pilot->name : 'N/A';
                $serviceType = ucfirst($pilotage->service_type);
                $duration = $pilotage->actual_end->diffInHours($pilotage->actual_start);
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => "Pilotage Service - {$serviceType} ({$pilotName}, {$duration} hrs)",
                    'quantity' => 1,
                    'unit_price' => $pilotage->calculated_fee,
                    'total_price' => $pilotage->calculated_fee
                ]);

                $totalAmount += $pilotage->calculated_fee;
            }
        }

        // Load towage requests
        $towageRequests = $portCall->towageRequests()->where('status', 'completed')->with('tugboat')->get();
        foreach ($towageRequests as $towage) {
            if ($towage->calculated_fee > 0) {
                $tugboatName = $towage->tugboat ? $towage->tugboat->name : 'N/A';
                $serviceType = ucfirst($towage->service_type);
                $tugsRequired = $towage->tugboats_required;
                $duration = $towage->actual_end->diffInHours($towage->actual_start);
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => "Towage Service - {$serviceType} ({$tugboatName}, {$tugsRequired} tug(s), {$duration} hrs)",
                    'quantity' => 1,
                    'unit_price' => $towage->calculated_fee,
                    'total_price' => $towage->calculated_fee
                ]);

                $totalAmount += $towage->calculated_fee;
            }
        }

        // 7. Update Invoice Totals
        $invoice->update([
            'total_amount' => $totalAmount,
            // If vessel has departed (ATD), we can consider issuing the invoice
            'status' => $portCall->atd ? 'issued' : 'draft'
        ]);

        return $invoice;
    }
}
