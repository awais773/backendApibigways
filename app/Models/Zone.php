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
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class,'id');
    }
    public function getVehiclesAttribute()
    {
        return Vehicle::whereIn('id', $this->vehicle_id)->get(['id', 'name', 'vehicle_type','reg_no','seating_capacity','per_km']);

    }
    public function pickup_points()
    {
        return $this->belongsTo(PickupPoint::class);
    }
}
