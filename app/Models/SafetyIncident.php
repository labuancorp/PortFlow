<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafetyIncident extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'organization_id',
        'type',
        'severity',
        'location',
        'description',
        'image_path',
        'status',
        'corrective_action',
        'reported_at'
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
