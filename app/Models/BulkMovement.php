<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkMovement extends Model
{
    protected $fillable = [
        'movement_type',
        'product_id',
        'source_tank_id',
        'destination_tank_id',
        'vessel_id',
        'weighbridge_ticket_id',
        'planned_volume',
        'actual_volume',
        'start_time',
        'end_time',
        'status',
        'remarks',
    ];

    protected $casts = [
        'planned_volume' => 'decimal:2',
        'actual_volume' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function sourceTank()
    {
        return $this->belongsTo(Tank::class, 'source_tank_id');
    }

    public function destinationTank()
    {
        return $this->belongsTo(Tank::class, 'destination_tank_id');
    }

    public function ticket()
    {
        return $this->belongsTo(WeighbridgeTicket::class, 'weighbridge_ticket_id');
    }
}
