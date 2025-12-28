<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_asset_id',
        'type',
        'description',
        'performed_at',
        'next_service_due',
        'cost',
        'technician_name',
        'status',
        'attachments'
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'next_service_due' => 'datetime',
        'attachments' => 'array',
    ];

    public function asset()
    {
        return $this->belongsTo(PortAsset::class, 'port_asset_id');
    }
}
