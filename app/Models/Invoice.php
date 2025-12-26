<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'port_call_id',
        'organization_id',
        'invoice_no',
        'total_amount',
        'status',
        'issued_date',
        'due_date',
        'erp_status',
        'erp_synced_at',
        'erp_reference_id',
        'erp_logs'
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function portCall()
    {
        return $this->belongsTo(PortCall::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
