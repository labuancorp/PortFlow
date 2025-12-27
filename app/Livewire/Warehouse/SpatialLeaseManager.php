<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SpatialLease;
use App\Models\WarehouseZone;
use App\Models\Organization;
use App\Services\AuditService;
use Illuminate\Support\Str;

class SpatialLeaseManager extends Component
{
    use WithPagination;

    public $showLeaseModal = false;
    public $selectedZoneId;
    
    // Form fields
    public $organization_id, $warehouse_zone_id, $leased_area_sqm, $rate_per_sqm, $start_date, $end_date, $notes;

    protected $rules = [
        'organization_id' => 'required|exists:organizations,id',
        'warehouse_zone_id' => 'required|exists:warehouse_zones,id',
        'leased_area_sqm' => 'required|numeric|min:1',
        'rate_per_sqm' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'notes' => 'nullable|string',
    ];

    public function openLeaseModal($zoneId = null)
    {
        $this->warehouse_zone_id = $zoneId;
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonths(6)->format('Y-m-d');
        $this->showLeaseModal = true;
    }

    public function saveLease()
    {
        $this->validate();

        $lease = SpatialLease::create([
            'organization_id' => $this->organization_id,
            'warehouse_zone_id' => $this->warehouse_zone_id,
            'reference_no' => 'LSE-' . strtoupper(Str::random(8)),
            'leased_area_sqm' => $this->leased_area_sqm,
            'rate_per_sqm' => $this->rate_per_sqm,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'active',
            'notes' => $this->notes,
        ]);

        AuditService::log('Create', 'Leases', $lease->id, "Issued spatial lease #{$lease->reference_no} for {$lease->leased_area_sqm}sqm");

        $this->showLeaseModal = false;
        $this->reset(['organization_id', 'leased_area_sqm', 'rate_per_sqm', 'notes']);
        $this->dispatch('notify', message: 'Spatial lease issued successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.warehouse.spatial-lease-manager', [
            'leases' => SpatialLease::with(['organization', 'zone.warehouse'])->latest()->paginate(10),
            'zones' => WarehouseZone::with('warehouse')->get(),
            'organizations' => Organization::where('type', 'agent')->orWhere('type', 'client')->get(),
            'stats' => [
                'total_revenue' => SpatialLease::where('status', 'active')->sum(\Illuminate\Support\Facades\DB::raw('leased_area_sqm * rate_per_sqm')),
                'active_leases' => SpatialLease::where('status', 'active')->count(),
                'leased_area' => SpatialLease::where('status', 'active')->sum('leased_area_sqm'),
            ]
        ]);
    }
}
