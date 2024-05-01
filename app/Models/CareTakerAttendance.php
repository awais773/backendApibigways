<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareTakerAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function careTaker() {
        return $this->hasOne(CareTaker::class, 'id', 'careTaker_id');  
    }

}
