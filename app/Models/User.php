<?php


namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function vehicle() {
        return $this->hasOne(Vehicle::class, 'id', 'vehicle_id');
    }
    public function zone() {
        return $this->hasOne(Zone::class, 'id','zone_id');
    }
    public function school() {
        return $this->hasOne(School::class, 'id','school_id');
    }
    public function student()
{
    return $this->hasOne(Student::class,'parent_id');
}
    public function students()
{
    return $this->hasMany(Student::class,'parent_id');
}
}
