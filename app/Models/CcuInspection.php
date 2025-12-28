<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CcuInspection extends Model
{
    protected $fillable = [
        'container_id',
        'inspection_type',
        'condition_status',
        'notes',
        'passed',
        'inspector_name',
        'temperature_c'
    ];

    protected $casts = [
        'passed' => 'boolean',
        'temperature_c' => 'decimal:2'
    ];

    public function container()
    {
        return $this->belongsTo(CcuContainer::class);
    }
}
