<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseBillingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_type',
        'rate_per_m3_per_day',
        'dg_surcharge_percentage',
        'minimum_charge',
        'bulk_discount_threshold_m3',
        'bulk_discount_percentage',
        'penalty_after_days',
        'penalty_surcharge_percentage',
        'is_active'
    ];

    protected $casts = [
        'rate_per_m3_per_day' => 'decimal:2',
        'dg_surcharge_percentage' => 'decimal:2',
        'minimum_charge' => 'decimal:2',
        'bulk_discount_threshold_m3' => 'decimal:2',
        'bulk_discount_percentage' => 'decimal:2',
        'penalty_surcharge_percentage' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}
