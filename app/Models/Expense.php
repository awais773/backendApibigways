<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function vehicle() {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
    public function driver() {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }
}
