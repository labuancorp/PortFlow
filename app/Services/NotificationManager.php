<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\PortCall;
use App\Models\CargoItem;
use App\Models\AssetBooking;
use App\Models\User;
use App\Models\Pilot;
use App\Models\Tugboat;
use App\Models\FuelInventory;
use Carbon\Carbon;

class NotificationManager
{
    /**
     * Check all operations and create notifications for approaching deadlines and overdues
     */
    public function checkAndNotify()
    {
        $this->checkMarineOperations();
        $this->checkYardStorage();
        $this->checkAssetRentals();
        $this->checkMaritimeServices(); // Phase 6
    }

    /**
     * Check Marine Operations (Port Calls) for approaching ETD and overdues
     */
    private function checkMarineOperations()
    {
        $activeCalls = PortCall::with(['agent', 'vessel'])
            ->whereIn('status', ['alongside', 'anchored'])
            ->whereNotNull('etd')
            ->get();

        foreach ($activeCalls as $call) {
            if (!$call->etd) continue;

            $now = now();
            $etd = Carbon::parse($call->etd);
            $minutesUntilDeparture = $now->diffInMinutes($etd, false);

            // 30-minute warning
            if ($minutesUntilDeparture > 0 && $minutesUntilDeparture <= 30) {
                $this->createWarningNotification(
                    $call->agent_id,
                    'marine',
                    "Vessel Departure Approaching",
                    "{$call->vessel->name} is scheduled to depart in {$minutesUntilDeparture} minutes. Please ensure all operations are completed.",
                    $call,
                    $etd
                );
            }

            // Overdue notification
            if ($minutesUntilDeparture < 0) {
                $hoursOverdue = abs($now->diffInHours($etd));
                $this->createOverdueNotification(
                    $call->agent_id,
                    'marine',
                    "Vessel Overstaying - Action Required",
                    "{$call->vessel->name} has exceeded scheduled departure by {$hoursOverdue} hours. Additional charges may apply.",
                    $call,
                    $etd
                );
            }
        }
    }

    /**
     * Check Yard Storage for long-term storage warnings
     */
    private function checkYardStorage()
    {
        $storedItems = CargoItem::with(['manifest.agent'])
            ->whereNull('discharged_at')
            ->whereNotNull('received_at')
            ->get();

        foreach ($storedItems as $item) {
            if (!$item->received_at) continue;

            $daysStored = now()->diffInDays($item->received_at);

            // Warning at 25 days (5 days before 30-day penalty threshold)
            if ($daysStored >= 25 && $daysStored < 30) {
                $daysUntilPenalty = 30 - $daysStored;
                $this->createWarningNotification(
                    $item->manifest->agent_id,
                    'yard',
                    "Long-Term Storage Warning",
                    "Cargo {$item->tracking_number} has been stored for {$daysStored} days. Penalty charges will apply in {$daysUntilPenalty} days.",
                    $item,
                    now()->addDays($daysUntilPenalty)
                );
            }

            // Overdue notification (30+ days)
            if ($daysStored >= 30) {
                $this->createOverdueNotification(
                    $item->manifest->agent_id,
                    'yard',
                    "Long-Term Storage Penalty Applied",
                    "Cargo {$item->tracking_number} has exceeded 30-day storage limit ({$daysStored} days). Penalty surcharges are now being applied.",
                    $item,
                    now()->subDays($daysStored - 30)
                );
            }
        }
    }

    /**
     * Check Asset Rentals for approaching return deadlines
     */
    private function checkAssetRentals()
    {
        $activeBookings = AssetBooking::with(['asset', 'organization'])
            ->where('status', 'active')
            ->whereNotNull('start_time')
            ->get();

        foreach ($activeBookings as $booking) {
            // Calculate expected return time (assuming 24-hour rental period if no end_time set)
            $expectedReturn = $booking->end_time ?? $booking->start_time->addHours(24);
            $now = now();
            $minutesUntilReturn = $now->diffInMinutes($expectedReturn, false);

            // 30-minute warning
            if ($minutesUntilReturn > 0 && $minutesUntilReturn <= 30) {
                $this->createWarningNotification(
                    $booking->organization_id,
                    'asset',
                    "Equipment Return Due Soon",
                    "{$booking->asset->name} rental is ending in {$minutesUntilReturn} minutes. Please arrange for return.",
                    $booking,
                    $expectedReturn
                );
            }

            // Overdue notification
            if ($minutesUntilReturn < 0) {
                $hoursOverdue = abs($now->diffInHours($expectedReturn));
                $this->createOverdueNotification(
                    $booking->organization_id,
                    'asset',
                    "Equipment Overdue - Late Fees Apply",
                    "{$booking->asset->name} is overdue by {$hoursOverdue} hours. Late fees are being charged.",
                    $booking,
                    $expectedReturn
                );
            }
        }
    }

    /**
     * Check Maritime Services (Phase 6: Pilotage, Towage, Fuel)
     */
    private function checkMaritimeServices()
    {
        // Check Pilot Availability
        $totalPilots = Pilot::count();
        $availablePilots = Pilot::where('status', 'available')->count();
        
        if ($totalPilots > 0 && $availablePilots == 0) {
            // All pilots are on duty - notify admin
            $this->createSystemNotification(
                'maritime',
                'All Pilots On Duty',
                "All {$totalPilots} pilots are currently on duty. No pilots available for new assignments."
            );
        } elseif ($totalPilots > 0 && $availablePilots <= 1) {
            // Only 1 pilot available - warning
            $this->createSystemNotification(
                'maritime',
                'Low Pilot Availability',
                "Only {$availablePilots} pilot(s) available out of {$totalPilots}. Consider scheduling carefully."
            );
        }

        // Check Tugboat Availability
        $totalTugboats = Tugboat::count();
        $availableTugboats = Tugboat::where('status', 'available')->count();
        
        if ($totalTugboats > 0 && $availableTugboats == 0) {
            // All tugboats in service - notify admin
            $this->createSystemNotification(
                'maritime',
                'All Tugboats In Service',
                "All {$totalTugboats} tugboats are currently in service. No tugboats available for new assignments."
            );
        } elseif ($totalTugboats > 0 && $availableTugboats <= 1) {
            // Only 1 tugboat available - warning
            $this->createSystemNotification(
                'maritime',
                'Low Tugboat Availability',
                "Only {$availableTugboats} tugboat(s) available out of {$totalTugboats}. Consider scheduling carefully."
            );
        }

        // Check Fuel Inventory Levels
        $lowStockItems = FuelInventory::lowStock()->get();
        foreach ($lowStockItems as $fuel) {
            $percentage = $fuel->getStockPercentage();
            $this->createSystemNotification(
                'maritime',
                "Low Fuel Stock: " . strtoupper($fuel->fuel_type),
                "Fuel inventory for {$fuel->fuel_type} is low ({$fuel->current_stock} {$fuel->unit}, {$percentage}% capacity). Current stock: {$fuel->current_stock} {$fuel->unit}. Minimum threshold: {$fuel->minimum_threshold} {$fuel->unit}."
            );
        }

        // Check Critical Fuel Levels
        $criticalStockItems = FuelInventory::criticalStock()->get();
        foreach ($criticalStockItems as $fuel) {
            $percentage = $fuel->getStockPercentage();
            $this->createSystemNotification(
                'maritime',
                "CRITICAL: " . strtoupper($fuel->fuel_type) . " Stock",
                "URGENT: Fuel inventory for {$fuel->fuel_type} is critically low ({$percentage}% capacity). Immediate restocking required! Current: {$fuel->current_stock} {$fuel->unit}."
            );
        }
    }

    /**
     * Create a warning notification (30-min before deadline)
     */
    private function createWarningNotification($orgId, $category, $title, $message, $reference, $deadline)
    {
        // Check if notification already exists for this reference
        $exists = Notification::where('organization_id', $orgId)
            ->where('reference_type', get_class($reference))
            ->where('reference_id', $reference->id)
            ->where('type', 'warning')
            ->where('created_at', '>=', now()->subHours(1)) // Don't spam
            ->exists();

        if ($exists) return;

        Notification::create([
            'organization_id' => $orgId,
            'type' => 'warning',
            'category' => $category,
            'title' => $title,
            'message' => $message,
            'reference_type' => get_class($reference),
            'reference_id' => $reference->id,
            'deadline_at' => $deadline,
        ]);

        // Also notify admin
        $this->notifyAdmin($orgId, $category, $title, $message, $reference);
    }

    /**
     * Create an overdue notification
     */
    private function createOverdueNotification($orgId, $category, $title, $message, $reference, $deadline)
    {
        // Check if notification already exists
        $exists = Notification::where('organization_id', $orgId)
            ->where('reference_type', get_class($reference))
            ->where('reference_id', $reference->id)
            ->where('type', 'overdue')
            ->where('created_at', '>=', now()->subDay()) // One per day
            ->exists();

        if ($exists) return;

        Notification::create([
            'organization_id' => $orgId,
            'type' => 'overdue',
            'category' => $category,
            'title' => $title,
            'message' => $message,
            'reference_type' => get_class($reference),
            'reference_id' => $reference->id,
            'deadline_at' => $deadline,
        ]);

        // Notify admin with urgency
        $this->notifyAdmin($orgId, $category, "OVERDUE: " . $title, $message, $reference);
    }

    /**
     * Notify admin users about agent issues
     */
    private function notifyAdmin($orgId, $category, $title, $message, $reference)
    {
        $admins = User::where('role', 'admin')->get();
        $org = \App\Models\Organization::find($orgId);

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'organization_id' => $orgId,
                'type' => 'info',
                'category' => $category,
                'title' => "[{$org->name}] {$title}",
                'message' => $message,
                'reference_type' => get_class($reference),
                'reference_id' => $reference->id,
            ]);
        }
    }

    /**
     * Create a system notification (admin-only, for maritime services)
     */
    private function createSystemNotification($category, $title, $message)
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            // Check if notification already exists in last hour (prevent spam)
            $exists = Notification::where('user_id', $admin->id)
                ->where('category', $category)
                ->where('title', $title)
                ->where('created_at', '>=', now()->subHours(1))
                ->exists();

            if ($exists) continue;

            Notification::create([
                'user_id' => $admin->id,
                'type' => 'info',
                'category' => $category,
                'title' => $title,
                'message' => $message,
            ]);
        }
    }

    /**
     * Get unread notifications for a user/organization
     */
    public function getUnreadCount($userId = null, $orgId = null)
    {
        $query = Notification::where('is_read', false);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($orgId) {
            $query->orWhere('organization_id', $orgId);
        }

        return $query->count();
    }
}
