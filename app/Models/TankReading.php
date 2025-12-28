<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankReading extends Model
{
    protected $fillable = [
        'tank_id',
        'reading_volume',
        'temperature',
        'source',
        'recorded_by',
        'recorded_at',
    ];

    protected $casts = [
        'reading_volume' => 'decimal:2',
        'temperature' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
