<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DgDeclaration extends Model
{
    protected $fillable = [
        'reference_type',
        'reference_id',
        'un_number',
        'proper_shipping_name',
        'dg_class_id',
        'packing_group',
        'flash_point',
        'neq_kg',
        'emergency_contact'
    ];

    protected $casts = [
        'flash_point' => 'decimal:2',
        'neq_kg' => 'decimal:4'
    ];

    public function dgClass()
    {
        return $this->belongsTo(DgClass::class, 'dg_class_id');
    }
}
