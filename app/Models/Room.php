<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'floor', 'block', 'capacity', 'current_occupancy'
    ];

    //Bir odada birden fazla öğrenci olabilir
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
