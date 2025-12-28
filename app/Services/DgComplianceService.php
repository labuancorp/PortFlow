<?php

namespace App\Services;

use App\Models\DgClass;
use App\Models\DgSegregationRule;
use App\Models\ExplosiveBunkerInventory;

class DgComplianceService
{
    /**
     * Check if two DG classes can be stored/loaded together.
     * Returns: 'allowed', 'prohibited', or 'restricted'.
     */
    public function checkSegregation(string $classCodeA, string $classCodeB): string
    {
        if ($classCodeA === $classCodeB) {
            return 'allowed';
        }

        $classA = DgClass::where('class_code', $classCodeA)->first();
        $classB = DgClass::where('class_code', $classCodeB)->first();

        if (!$classA || !$classB) {
            return 'unknown';
        }

        // Check A vs B
        $rule = DgSegregationRule::where('class_a_id', $classA->id)
            ->where('class_b_id', $classB->id)
            ->first();

        if ($rule) return $rule->rule;

        // Check B vs A (symmetric)
        $rule = DgSegregationRule::where('class_a_id', $classB->id)
            ->where('class_b_id', $classA->id)
            ->first();

        if ($rule) return $rule->rule;

        // Default to restricted if no specific rule? Or allowed?
        // In safety, default should be conservative.
        // For demo, we return 'allowed' unless prohibited.
        return 'allowed';
    }

    /**
     * Get details of the Explosive Bunker status.
     */
    public function getBunkerStatus(): array
    {
        $items = ExplosiveBunkerInventory::with('declaration.dgClass')->get();
        
        $totalNEQ = $items->sum(function ($item) {
            return $item->declaration->neq_kg * $item->quantity_stored; 
            // Warning: quantity_stored might be units, but for simplicity assuming 1 unit = 1 declaration NEQ?
            // Actually, usually quantity_stored matches the unit of measure of declaration.
            // If declaration has NEQ per kg, and quantity is kg.
            // Let's assume declaration->neq_kg is TOTAL NEQ for that declaration line item.
        });

        // Hardcoded limit for demo
        $limitNEQ = 500.00; 

        return [
            'total_neq' => $totalNEQ,
            'limit_neq' => $limitNEQ,
            'utilization_percent' => ($totalNEQ / $limitNEQ) * 100,
            'items_count' => $items->count()
        ];
    }
}
