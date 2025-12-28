<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeighbridgeTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'truck_plate_number',
        'gross_weight',
        'tare_weight',
        'net_weight',
        'status',
        'issued_at',
    ];

    protected $casts = [
        'gross_weight' => 'decimal:2',
        'tare_weight' => 'decimal:2',
        'net_weight' => 'decimal:2',
        'issued_at' => 'datetime',
    ];

    public function movement()
    {
        return $this->hasOne(BulkMovement::class);
    }
}
