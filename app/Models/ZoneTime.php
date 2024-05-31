<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneTime extends Model
{
    protected $guarded = [];

    use HasFactory;
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class,'id');
    }
    public function getVehiclesAttribute()
    {
        $vehicle = Vehicle::find($this->vehicle_id);
        if ($vehicle) {
            $vehicles[] = [
                'id' => $vehicle->id,
                'name' => $vehicle->name,
                'vehicle_type' => $vehicle->vehicle_type,
                'reg_no' => $vehicle->reg_no,
                'seating_capacity' => $vehicle->seating_capacity,
                'per_km' => $vehicle->per_km,
            ];
        }
        return $vehicle;
    }

    public function zones(){
        return $this->hasMany(Zone::class, 'id','zone_id');
    }
}
