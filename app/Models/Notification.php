<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'type',
        'category',
        'title',
        'message',
        'reference_type',
        'reference_id',
        'amount',
        'deadline_at',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
