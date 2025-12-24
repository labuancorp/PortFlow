<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewTransfer extends Model
{
    protected $fillable = ['port_call_id', 'crew_member_id', 'direction', 'status', 'scanned_at'];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function portCall()
    {
        return $this->belongsTo(PortCall::class);
    }

    public function crewMember()
    {
        return $this->belongsTo(CrewMember::class);
    }
}
