<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CcuContainer extends Model
{
    protected $fillable = [
        'container_number',
        'type',
        'size',
        'owner',
        'status',
        'location_yard_zone',
        'current_vessel_id',
        'last_inspection_date',
        'sling_cert_expiry',
        'gate_in_date'
    ];

    protected $casts = [
        'last_inspection_date' => 'date',
        'sling_cert_expiry' => 'date',
        'gate_in_date' => 'datetime'
    ];

    public function movements()
    {
        return $this->hasMany(CcuMovement::class, 'container_id');
    }

    public function inspections()
    {
        return $this->hasMany(CcuInspection::class, 'container_id');
    }
}
