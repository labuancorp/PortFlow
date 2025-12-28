<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCompatibility extends Model
{
    protected $table = 'product_compatibility';

    protected $fillable = [
        'product_a_id',
        'product_b_id',
        'is_compatible',
        'notes',
    ];

    protected $casts = [
        'is_compatible' => 'boolean',
    ];

    public function productA()
    {
        return $this->belongsTo(ProductType::class, 'product_a_id');
    }

    public function productB()
    {
        return $this->belongsTo(ProductType::class, 'product_b_id');
    }
}
