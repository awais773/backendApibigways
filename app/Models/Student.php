<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parent() {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function vehicle() {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
    public function driver() {
        return $this->hasOne(Driver::class, 'vehicle_id');
    }
}
