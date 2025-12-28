<?php

namespace App\Services;

use App\Models\PortCall;
use App\Models\CargoItem;
use App\Models\PortAsset;
use App\Models\SafetyIncident;
use App\Models\AssetBooking;
use App\Models\Invoice;
use App\Models\Notification;

class NotificationService
{
    public static function getPendingCounts()
    {
        $user = auth()->user();
        
        return [
            // Core Operations
            'berth_planner' => PortCall::where('status', 'requested')->count(),
            
            // Marine & Logistics
            'cargo_manifests' => CargoItem::where('status', 'pending')->count(),
            'yard_approvals' => 0, // Placeholder if yard requests logic exists

            // Assets & Facilities
            'asset_inventory' => AssetBooking::where('status', 'requested')->count(),
            
            // Safety
            'hse_incidents' => SafetyIncident::where('status', 'open')->count(),
            'permits' => 0, // Placeholder
            
            // Admin
            'agent_registry' => 0, // Placeholder for pending agent approvals
            'billing' => Invoice::where('status', 'draft')->count(),
            
            // Notifications
            'notifications' => $user->role === 'admin' 
                ? Notification::where('user_id', $user->id)->where('is_read', false)->count()
                : Notification::where('organization_id', $user->organization_id)->where('is_read', false)->count(),
        ];
    }
}
