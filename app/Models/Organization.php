<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'type', 'code', 'billing_address'];

    public function vessels()
    {
        return $this->hasMany(Vessel::class);
    }

    public function portCalls()
    {
        return $this->hasMany(PortCall::class, 'agent_id');
    }
}
