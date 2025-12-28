<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berth extends Model
{
    protected $fillable = ['name', 'code', 'max_loa', 'max_draft', 'status', 'latitude', 'longitude', 'color'];

    public function portCalls()
    {
        return $this->hasMany(PortCall::class, 'assigned_berth_id');
    }

    public function currentPortCall()
    {
        return $this->hasOne(PortCall::class, 'assigned_berth_id')->where('status', 'alongside')->latest();
    }
}
