<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'license_number',
        'phone',
        'email',
        'status',
        'certifications',
        'license_expiry',
        'rate_per_hour',
        'notes',
    ];

    protected $casts = [
        'certifications' => 'array',
        'license_expiry' => 'date',
        'rate_per_hour' => 'decimal:2',
    ];

    // Relationships
    public function pilotageRequests()
    {
        return $this->hasMany(PilotageRequest::class);
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isLicenseValid()
    {
        return $this->license_expiry->isFuture();
    }

    public function canPilotVessel($vesselType)
    {
        if (!$this->certifications) {
            return false;
        }
        return in_array($vesselType, $this->certifications);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->whereDate('license_expiry', '>', now());
    }
}
