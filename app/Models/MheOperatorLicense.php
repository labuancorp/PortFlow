<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MheOperatorLicense extends Model
{
    protected $fillable = [
        'user_id',
        'license_type',
        'license_number',
        'expiry_date',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
