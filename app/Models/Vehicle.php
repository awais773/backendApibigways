<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
   protected $guarded = [];

   public function driver() {
    return $this->belongsTo(Driver::class, 'id', 'vehicle_id');
}
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function student()
    {
        return $this->belongsTo(Student::class , 'id');
    }
}
