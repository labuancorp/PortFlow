<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpatialLease extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'warehouse_zone_id',
        'reference_no',
        'leased_area_sqm',
        'area_coordinates',
        'rate_per_sqm',
        'start_date',
        'end_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'area_coordinates' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function zone()
    {
        return $this->belongsTo(WarehouseZone::class, 'warehouse_zone_id');
    }
}
