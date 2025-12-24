<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berth extends Model
{
    protected $fillable = ['name', 'code', 'max_loa', 'max_draft', 'status'];

    public function portCalls()
    {
        return $this->hasMany(PortCall::class, 'assigned_berth_id');
    }
}
