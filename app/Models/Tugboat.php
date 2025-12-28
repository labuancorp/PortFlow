<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugboat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_number',
        'bollard_pull_tons',
        'status',
        'rate_per_hour',
        'certificate_expiry',
        'captain_name',
        'captain_phone',
        'notes',
    ];

    protected $casts = [
        'bollard_pull_tons' => 'integer',
        'rate_per_hour' => 'decimal:2',
        'certificate_expiry' => 'date',
    ];

    // Relationships
    public function towageRequests()
    {
        return $this->hasMany(TowageRequest::class);
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isCertificateValid()
    {
        return $this->certificate_expiry && $this->certificate_expiry->isFuture();
    }

    public function canTowVessel($vesselGRT)
    {
        // Rule: Tugboat bollard pull should be at least 10% of vessel GRT
        $requiredPull = $vesselGRT * 0.10;
        return $this->bollard_pull_tons >= $requiredPull;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->where(function($q) {
                         $q->whereNull('certificate_expiry')
                           ->orWhereDate('certificate_expiry', '>', now());
                     });
    }

    public function scopeCapableOf($query, $requiredPull)
    {
        return $query->where('bollard_pull_tons', '>=', $requiredPull);
    }
}
