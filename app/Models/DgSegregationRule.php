<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DgSegregationRule extends Model
{
    protected $fillable = ['class_a_id', 'class_b_id', 'rule'];

    public function classA()
    {
        return $this->belongsTo(DgClass::class, 'class_a_id');
    }

    public function classB()
    {
        return $this->belongsTo(DgClass::class, 'class_b_id');
    }
}
