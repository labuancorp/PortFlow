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
        'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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
