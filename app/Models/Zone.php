<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'vehicle_id' => 'array',
        'mid_name' => 'array',
        'mid_latitude' => 'array',
        'mid_longitude' => 'array',
        'pickup_time' => 'array',
        'return_time' => 'array',
        'id' => 'array',
    ];

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
    public function schools(){
        return $this->belongsTo(School::class, 'schools_id');
    }
    public function zonetimes()
    {
        return $this->hasMany(ZoneTime::class);
    }
}
