<?php

namespace App\Services;

use App\Models\MheEquipment;
use App\Models\MheMaintenanceLog;
use App\Models\MheBooking;
use Carbon\Carbon;

class MheService
{
    /**
     * Get statistics for the dashboard.
     */
    public function getFleetStats(): array
    {
        $total = MheEquipment::count();
        $available = MheEquipment::where('status', 'available')->count();
        $maintenance = MheEquipment::where('status', 'maintenance')->count();
        $breakdown = MheEquipment::where('status', 'breakdown')->count();
        
        $utilization = $total > 0 ? (($total - $available) / $total) * 100 : 0; // Simple utilization proxy

        return compact('total', 'available', 'maintenance', 'breakdown', 'utilization');
    }

    /**
     * Check if a specific type is available for a time slot.
     */
    public function checkAvailability(string $type, Carbon $start, Carbon $end): int
    {
        // 1. Find all equipment of type
        $totalEq = MheEquipment::where('type', $type)
            ->where('status', '!=', 'breakdown')
            ->where('status', '!=', 'maintenance')
            ->pluck('id');

        // 2. Find booked equipment overlapping time
        $bookedEq = MheBooking::whereIn('equipment_id', $totalEq)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                  ->orWhereBetween('end_time', [$start, $end])
                  ->orWhere(function ($sub) use ($start, $end) {
                      $sub->where('start_time', '<', $start)
                          ->where('end_time', '>', $end);
                  });
            })
            ->pluck('equipment_id');

        return $totalEq->diff($bookedEq)->count();
    }

    /**
     * Log a maintenance event.
     */
    public function logMaintenance(MheEquipment $equipment, string $type, string $desc, float $cost = 0)
    {
        MheMaintenanceLog::create([
            'equipment_id' => $equipment->id,
            'type' => $type,
            'description' => $desc,
            'parts_cost' => $cost,
            'service_date' => now(),
        ]);

        if ($type === 'PM') {
             $equipment->update([
                 'status' => 'available',
                 'next_pm_due_date' => now()->addMonths(3), // Default interval
                 'next_pm_due_hours' => $equipment->current_hour_meter + 250
             ]);
        }
    }
}
