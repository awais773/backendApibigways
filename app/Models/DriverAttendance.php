<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function driver() {
        return $this->hasOne(Driver::class, 'id', 'driver_id');  
    }
}
