<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CcuMovement extends Model
{
    protected $fillable = [
        'container_id',
        'movement_type',
        'location_from',
        'location_to',
        'vessel_name',
        'truck_plate',
        'occurred_at',
        'handled_by'
    ];

    protected $casts = [
        'occurred_at' => 'datetime'
    ];

    public function container()
    {
        return $this->belongsTo(CcuContainer::class);
    }
}
