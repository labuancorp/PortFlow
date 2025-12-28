<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnchorageZone extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'boundary_coordinates' => 'array',
    ];

    public function portCalls()
    {
        return $this->hasMany(PortCall::class)->where('status', 'anchored');
    }

    public function getCurrentUsageAttribute()
    {
        return $this->portCalls()->count();
    }
}
