<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    protected $fillable = [
        'organization_id',
        'agent_id',
        'name',
        'imo_number',
        'flag_country',
        'loa_meters',
        'draft_meters',
        'vessel_type',
        'status'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function portCalls()
    {
        return $this->hasMany(PortCall::class);
    }
}
