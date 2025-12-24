<?php

namespace App\Services;

use App\Models\Berth;
use App\Models\Vessel;
use App\Models\PortCall;
use Carbon\Carbon;

class BerthOptimizationService
{
    /**
     * Find and rank efficient berths for a given vessel and time window.
     *
     * @param Vessel $vessel
     * @param string|Carbon $eta
     * @param string|Carbon $etd
     * @return array List of berths with scores and reasoning
     */
    public function findOptimalBerths(Vessel $vessel, $eta, $etd)
    {
        $eta = Carbon::parse($eta);
        $etd = Carbon::parse($etd);

        // 1. Filter by Physical Constraints (LOA & Draft)
        $candidates = Berth::where('max_loa', '>=', $vessel->loa_meters)
            ->where('max_draft', '>=', $vessel->draft_meters)
            ->where('status', 'active') // Only consider active berths
            ->get();

        $results = [];

        foreach ($candidates as $berth) {
            // 2. Check for Time Collisions
            $isOccupied = PortCall::where('assigned_berth_id', $berth->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($eta, $etd) {
                    $query->where('eta', '<', $etd)
                          ->where('etd', '>', $eta);
                })
                ->exists();

            if ($isOccupied) {
                continue; // Skip occupied berths
            }

            // 3. Calculate "Fit Score" (Lower is better)
            // We want the tightest fit effectively.
            // LOA tightness: (berth_loa - vessel_loa)
            // Draft tightness: (berth_draft - vessel_draft) * 10 (Weight draft higher as it's more critical/scarce)
            
            $loaDiff = $berth->max_loa - $vessel->loa_meters;
            $draftDiff = $berth->max_draft - $vessel->draft_meters;

            $score = $loaDiff + ($draftDiff * 10);

            // Additional heuristic: Prefer wharfs (Main Wharf) over jetties if vessel is large? 
            // For now, simple geometric fit.

            $results[] = [
                'berth' => $berth,
                'score' => $score,
                'loa_diff' => $loaDiff,
                'draft_diff' => $draftDiff,
                'is_perfect_fit' => ($loaDiff < 20 && $draftDiff < 2)
            ];
        }

        // 4. Sort by Score (Ascending)
        usort($results, function ($a, $b) {
            return $a['score'] <=> $b['score'];
        });

        return $results;
    }
}
