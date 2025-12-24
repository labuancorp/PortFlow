<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewMember extends Model
{
    protected $fillable = ['name', 'passport_number', 'nationality', 'date_of_birth'];

    public function transfers()
    {
        return $this->hasMany(CrewTransfer::class);
    }
}
