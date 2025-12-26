<?php

namespace App\Services;

use App\Models\Berth;
use App\Models\Vessel;
use App\Models\PortCall;
use Carbon\Carbon;

/**
 * BerthOptimizationService
 * 
 * AI-powered berth allocation using constraint satisfaction algorithm.
 * Enhanced with scoring, conflict detection, and optimization recommendations.
 */
class BerthOptimizationService
{
    /**
     * Find and rank efficient berths for a given vessel and time window.
     * ENHANCED with AI scoring and detailed recommendations.
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
            ->where('status', 'active')
            ->get();

        $results = [];

        foreach ($candidates as $berth) {
            // 2. Check for Time Collisions
            $conflicts = $this->detectConflicts($berth->id, $eta, $etd);
            
            if ($conflicts->isNotEmpty()) {
                // Include unavailable berths with conflict info
                $results[] = [
                    'berth' => $berth,
                    'score' => 0,
                    'confidence' => 'unavailable',
                    'available' => false,
                    'conflicts' => $conflicts->map(fn($c) => [
                        'vessel' => $c->vessel->name,
                        'eta' => $c->eta->format('M d, H:i'),
                        'etd' => $c->etd->format('M d, H:i')
                    ])->toArray(),
                    'reasons' => ["❌ Berth occupied ({$conflicts->count()} conflict(s))"]
                ];
                continue;
            }

            // 3. Calculate AI Score (0-100, higher is better)
            $aiScore = $this->calculateAIScore($berth, $vessel, $eta, $etd);
            
            // 4. Get recommendation reasons
            $reasons = $this->getRecommendationReasons($berth, $vessel, $aiScore);

            $results[] = [
                'berth' => $berth,
                'score' => $aiScore,
                'confidence' => $this->calculateConfidence($aiScore),
                'available' => true,
                'conflicts' => [],
                'reasons' => $reasons,
                'loa_diff' => $berth->max_loa - $vessel->loa_meters,
                'draft_diff' => $berth->max_draft - $vessel->draft_meters,
                'is_perfect_fit' => $this->isPerfectFit($berth, $vessel)
            ];
        }

        // 5. Sort by AI Score (Descending - highest score first)
        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        return $results;
    }

    /**
     * Calculate AI-powered suitability score (0-100)
     */
    private function calculateAIScore($berth, $vessel, $eta, $etd)
    {
        $score = 100;
        
        // 1. Size efficiency (prefer berth that's not too oversized)
        $loaUtilization = ($vessel->loa_meters / $berth->max_loa) * 100;
        if ($loaUtilization >= 80 && $loaUtilization <= 95) {
            $score += 20; // Perfect fit
        } elseif ($loaUtilization < 50) {
            $score -= 30; // Wasting berth capacity
        }
        
        // 2. Draft safety margin
        $draftMargin = $berth->max_draft - $vessel->draft_meters;
        if ($draftMargin >= 2.0) {
            $score += 15; // Safe margin
        } elseif ($draftMargin < 0.5) {
            $score -= 40; // Risky
        }
        
        // 3. Historical performance
        $avgTurnaround = $this->getAverageTurnaroundTime($berth->id);
        if ($avgTurnaround < 4) {
            $score += 10; // Fast berth
        }
        
        // 4. Premium berth bonus
        if (str_contains($berth->name, 'Main Wharf')) {
            $score += 5;
        }
        
        // 5. Time slot efficiency
        $gapPenalty = $this->calculateGapPenalty($berth->id, $eta, $etd);
        $score -= $gapPenalty;
        
        return max(0, min(100, round($score)));
    }

    /**
     * Detect scheduling conflicts for a berth
     */
    public function detectConflicts($berthId, $eta, $etd, $excludePortCallId = null)
    {
        $query = PortCall::where('assigned_berth_id', $berthId)
            ->whereIn('status', ['requested', 'approved', 'alongside', 'anchored'])
            ->where(function($q) use ($eta, $etd) {
                // Check for any time overlap
                $q->whereBetween('eta', [$eta, $etd])
                  ->orWhereBetween('etd', [$eta, $etd])
                  ->orWhere(function($q2) use ($eta, $etd) {
                      $q2->where('eta', '<=', $eta)
                         ->where('etd', '>=', $etd);
                  });
            })
            ->with('vessel');
        
        if ($excludePortCallId) {
            $query->where('id', '!=', $excludePortCallId);
        }
        
        return $query->get();
    }

    /**
     * Calculate confidence level based on score
     */
    private function calculateConfidence($score)
    {
        if ($score >= 90) return 'very_high';
        if ($score >= 75) return 'high';
        if ($score >= 60) return 'medium';
        if ($score >= 40) return 'low';
        return 'very_low';
    }

    /**
     * Get human-readable reasons for recommendation
     */
    private function getRecommendationReasons($berth, $vessel, $score)
    {
        $reasons = [];
        
        // Size fit
        $loaUtilization = ($vessel->loa_meters / $berth->max_loa) * 100;
        if ($loaUtilization >= 80 && $loaUtilization <= 95) {
            $reasons[] = "⭐ Perfect size match (" . round($loaUtilization) . "% utilization)";
        } elseif ($loaUtilization < 50) {
            $reasons[] = "⚠️ Berth is oversized (only " . round($loaUtilization) . "% utilized)";
        } else {
            $reasons[] = "✅ Good size fit (" . round($loaUtilization) . "% utilization)";
        }
        
        // Draft safety
        $draftMargin = $berth->max_draft - $vessel->draft_meters;
        if ($draftMargin >= 2.0) {
            $reasons[] = "✅ Safe draft margin (" . round($draftMargin, 1) . "m clearance)";
        } elseif ($draftMargin >= 0.5) {
            $reasons[] = "✅ Adequate draft clearance (" . round($draftMargin, 1) . "m)";
        }
        
        // Performance
        $avgTurnaround = $this->getAverageTurnaroundTime($berth->id);
        if ($avgTurnaround < 4) {
            $reasons[] = "⚡ Fast turnaround berth (avg {$avgTurnaround}h)";
        }
        
        // Overall recommendation
        if ($score >= 90) {
            $reasons[] = "🏆 Highly recommended (Score: {$score}/100)";
        } elseif ($score >= 75) {
            $reasons[] = "✅ Good choice (Score: {$score}/100)";
        } else {
            $reasons[] = "ℹ️ Available (Score: {$score}/100)";
        }
        
        return $reasons;
    }

    /**
     * Check if vessel is a perfect fit for berth
     */
    private function isPerfectFit($berth, $vessel)
    {
        $loaDiff = $berth->max_loa - $vessel->loa_meters;
        $draftDiff = $berth->max_draft - $vessel->draft_meters;
        
        return ($loaDiff < 20 && $loaDiff > 5) && ($draftDiff < 2 && $draftDiff > 0.5);
    }

    /**
     * Get average turnaround time for a berth (in hours)
     */
    private function getAverageTurnaroundTime($berthId)
    {
        $completedCalls = PortCall::where('assigned_berth_id', $berthId)
            ->where('status', 'completed')
            ->whereNotNull('atb')
            ->whereNotNull('atd')
            ->limit(10) // Last 10 calls
            ->get();
        
        if ($completedCalls->isEmpty()) {
            return 6; // Default estimate
        }
        
        $totalHours = $completedCalls->sum(function($call) {
            return $call->atb->diffInHours($call->atd);
        });
        
        return round($totalHours / $completedCalls->count(), 1);
    }

    /**
     * Calculate penalty for creating scheduling gaps
     */
    private function calculateGapPenalty($berthId, $eta, $etd)
    {
        // Find nearest port calls before and after
        $before = PortCall::where('assigned_berth_id', $berthId)
            ->where('etd', '<=', $eta)
            ->orderBy('etd', 'desc')
            ->first();
        
        $after = PortCall::where('assigned_berth_id', $berthId)
            ->where('eta', '>=', $etd)
            ->orderBy('eta', 'asc')
            ->first();
        
        $penalty = 0;
        
        // Penalize large gaps
        if ($before && $before->etd->diffInHours($eta) > 24) {
            $penalty += 5;
        }
        
        if ($after && $etd->diffInHours($after->eta) > 24) {
            $penalty += 5;
        }
        
        return $penalty;
    }

    /**
     * Optimize entire schedule for a date range
     */
    public function optimizeSchedule($startDate, $endDate)
    {
        $portCalls = PortCall::whereBetween('eta', [$startDate, $endDate])
            ->whereIn('status', ['requested', 'approved'])
            ->with('vessel')
            ->get();
        
        $optimizations = [];
        
        foreach ($portCalls as $call) {
            $suggestions = $this->findOptimalBerths($call->vessel, $call->eta, $call->etd);
            
            if (!empty($suggestions) && $suggestions[0]['available']) {
                $bestBerth = $suggestions[0];
                
                if ($bestBerth['berth']->id != $call->assigned_berth_id) {
                    $optimizations[] = [
                        'port_call_id' => $call->id,
                        'vessel' => $call->vessel->name,
                        'current_berth' => $call->assignedBerth->name ?? 'None',
                        'suggested_berth' => $bestBerth['berth']->name,
                        'improvement_score' => $bestBerth['score'],
                        'reasons' => $bestBerth['reasons']
                    ];
                }
            }
        }
        
        return [
            'total_port_calls' => $portCalls->count(),
            'optimizations_found' => count($optimizations),
            'estimated_efficiency_gain' => count($optimizations) > 0 
                ? round((count($optimizations) / $portCalls->count()) * 100, 1) . '%'
                : '0%',
            'recommendations' => $optimizations
        ];
    }
}
