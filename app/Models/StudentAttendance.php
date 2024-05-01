<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'student_id');
    }
    public function vehicle()
    {
        return $this->hasOne(vehicle::class, 'id', 'vehicle_id');
    }
}
