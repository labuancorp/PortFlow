<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'current_stock',
        'min_threshold',
        'unit',
        'location'
    ];

    public function needsReorder()
    {
        return $this->current_stock <= $this->min_threshold;
    }
}
