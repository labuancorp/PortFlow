<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MheBooking extends Model
{
    protected $fillable = [
        'equipment_id',
        'operator_id',
        'job_type',
        'reference_id',
        'start_time',
        'end_time',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(MheEquipment::class, 'equipment_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
