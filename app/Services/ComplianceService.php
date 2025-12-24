<?php

namespace App\Services;

use App\Models\Berth;
use App\Models\Vessel;
use App\Models\PortCall;
use Carbon\Carbon;

class ComplianceService
{
    /**
     * Validate if a berthing operation is safe and compliant.
     *
     * @param Vessel $vessel
     * @param Berth $berth
     * @param string|Carbon $eta
     * @param string|Carbon $etd
     * @return array ['safe' => bool, 'messages' => array]
     */
    public function validateOperation(Vessel $vessel, Berth $berth, $eta, $etd)
    {
        $messages = [];
        $safe = true;

        // 1. Digital Constraint: Max LOA
        if ($vessel->loa_meters > $berth->max_loa) {
            $safe = false;
            $messages[] = "CRITICAL: Vessel Length ({$vessel->loa_meters}m) exceeds Berth Capacity ({$berth->max_loa}m). Risk of overhang collision.";
        }

        // 2. Digital Constraint: Max Draft (Grounding Risk)
        if ($vessel->draft_meters > $berth->max_draft) {
            $safe = false;
            $messages[] = "CRITICAL: Vessel Draft ({$vessel->draft_meters}m) exceeds Berth Depth ({$berth->max_draft}m). HIGH RISK OF GROUNDING.";
        }

        // 3. Maintenance Check
        if ($berth->status === 'maintenance') {
            $safe = false;
            $messages[] = "WARNING: Berth is currently flagged for Maintenance.";
        }

        // 4. Temporal Conflict (Double Booking)
        $conflicts = PortCall::where('assigned_berth_id', $berth->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($eta, $etd) {
                // Determine if Ranges Overlap
                // (StartA <= EndB) and (EndA >= StartB)
                $query->where('eta', '<', $etd)
                      ->where('etd', '>', $eta);
            })
            ->with('vessel')
            ->get();

        if ($conflicts->count() > 0) {
            $safe = false;
            foreach ($conflicts as $conflict) {
                $messages[] = "CONFLICT: Time slot overlaps with existing booking for '{$conflict->vessel->name}' ({$conflict->eta->format('H:i')} - {$conflict->etd->format('H:i')}).";
            }
        }

        // 5. License Expiry Simulation (Future Feature)
        // if ($vessel->license_expired) { ... }

        return [
            'safe' => $safe,
            'messages' => $messages
        ];
    }
}
