<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\CargoItem;
use App\Models\CargoManifest;

class YardMap extends Component
{
    public function render()
    {
        $user = auth()->user();
        $isSubscribed = false;
        
        // Check subscription for agents
        if ($user->role === 'agent') {
            $isSubscribed = $user->organization->warehouse_subscribed ?? false;
            
            if (!$isSubscribed) {
                return view('livewire.warehouse.yard-map', [
                    'subscribed' => false,
                    'warehouses' => collect(),
                    'agingItems' => collect()
                ]);
            }
        }

        // Fetch warehouses with zones and items
        $warehouses = Warehouse::with(['zones' => function($query) use ($user) {
            $query->with(['items' => function($itemQuery) use ($user) {
                $itemQuery->with(['manifest.agent']);
                
                // For agents: show their items + occupied zones (masked)
                if ($user->role === 'agent') {
                    // We'll filter in the view to show owner names for occupied zones
                    // but full details only for their own items
                }
            }]);
        }])->get();

        // Aging report
        $agingQuery = CargoItem::where('created_at', '<=', now()->subDays(90))
            ->whereNotIn('status', ['discharged', 'completed'])
            ->with(['manifest.agent', 'zone']);

        // Filter for agents
        if ($user->role === 'agent') {
            $agingQuery->whereHas('manifest', function($q) use ($user) {
                $q->where('agent_id', $user->organization_id);
            });
        }

        $agingItems = $agingQuery->latest()->take(10)->get();

        // Pending Requests for Agents
        $pendingRequests = collect();
        $myInventory = collect();
        if ($user->role === 'agent') {
            $pendingRequests = CargoManifest::where('agent_id', $user->organization_id)
                ->where('yard_storage_requested', true)
                ->where('status', '!=', 'completed')
                ->whereHas('items', function($q) {
                    $q->whereNull('warehouse_zone_id');
                })
                ->with(['vessel', 'items' => function($q) { $q->whereNull('warehouse_zone_id'); }])
                ->get();

            $myInventory = CargoItem::whereHas('manifest', function($q) use ($user) {
                    $q->where('agent_id', $user->organization_id);
                })
                ->whereNotNull('warehouse_zone_id')
                ->with(['zone', 'manifest'])
                ->get();
        }

        return view('livewire.warehouse.yard-map', [
            'subscribed' => true,
            'warehouses' => $warehouses,
            'agingItems' => $agingItems,
            'pendingRequests' => $pendingRequests,
            'myInventory' => $myInventory,
            'userOrgId' => $user->role === 'agent' ? $user->organization_id : null
        ]);
    }
}
