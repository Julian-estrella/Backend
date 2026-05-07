<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BloodType extends Model
{
    use SoftDeletes;
    //Relacion uno a muchos
    public function patients(){
        return $this->hasMany(patient::class);
    }
}
