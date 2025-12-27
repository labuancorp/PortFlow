<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['name', 'type', 'code', 'billing_address', 'warehouse_subscribed', 'enabled_modules'];

    protected $casts = [
        'enabled_modules' => 'array',
    ];

    public function vessels()
    {
        return $this->hasMany(Vessel::class);
    }

    public function portCalls()
    {
        return $this->hasMany(PortCall::class, 'agent_id');
    }

    public function hasModule($moduleName)
    {
        if (!$this->enabled_modules) {
            return false;
        }
        return in_array($moduleName, $this->enabled_modules);
    }
}
