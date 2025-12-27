<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class GateEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'driver_name',
        'driver_ic',
        'vehicle_plate',
        'cargo_description',
        'has_dangerous_goods',
        'status',
        'scanned_at',
        'gate_in_at',
        'gate_out_at',
    ];

    protected $casts = [
        'has_dangerous_goods' => 'boolean',
        'scanned_at' => 'datetime',
        'gate_in_at' => 'datetime',
        'gate_out_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}
