<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExplosiveBunkerInventory extends Model
{
    protected $table = 'explosive_bunker_inventory';

    protected $fillable = [
        'dg_declaration_id',
        'magazine_id',
        'quantity_stored',
        'expiry_date',
        'security_seal_number',
        'police_permit_number',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity_stored' => 'decimal:2'
    ];

    public function declaration()
    {
        return $this->belongsTo(DgDeclaration::class, 'dg_declaration_id');
    }
}
