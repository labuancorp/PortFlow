<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'port_call_id',
        'service_type',
        'quantity',
        'unit',
        'status',
        'requested_at'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
    ];

    public function portCall()
    {
        return $this->belongsTo(PortCall::class);
    }
}
