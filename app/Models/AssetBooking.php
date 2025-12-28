<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'port_asset_id',
        'start_time',
        'end_time',
        'estimated_cost',
        'total_cost',
        'status',
        'reference_no',
        'notes',
        'check_out_time',
        'check_in_time',
        'check_out_notes',
        'check_in_notes',
        'check_out_media',
        'check_in_media',
        'initial_engine_hours',
        'final_engine_hours'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'check_out_time' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_media' => 'array',
        'check_in_media' => 'array',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function asset()
    {
        return $this->belongsTo(PortAsset::class, 'port_asset_id');
    }
}
