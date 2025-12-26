<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotSensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'unit',
        'status',
        'last_reading_at',
        'threshold_warning',
        'threshold_critical'
    ];

    protected $casts = [
        'last_reading_at' => 'datetime',
        'value' => 'decimal:2'
    ];

    public function getRiskLevelAttribute()
    {
        if ($this->threshold_critical && $this->value >= $this->threshold_critical) {
            return 'critical';
        }
        if ($this->threshold_warning && $this->value >= $this->threshold_warning) {
            return 'warning';
        }
        return 'normal';
    }
}
