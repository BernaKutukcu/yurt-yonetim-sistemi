<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'tc_no', 'phone', 'city', 'address',
        'department', 'birth_date', 'iban', 'mother_name',
        'father_name', 'parent_phone', 'registration_date',
        'room_id', 'bed_number'
    ];

    //Bir öğrencinin bir tane kullanıcı hesabı vardır
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Bir öğrencinin bir odası vardır
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    //Bir öğrencinin birden fazla giriş ve çıkış kaydı vardır
    public function entryLogs()
    {
        return $this->hasMany(EntryLog::class);
    }

    //Bir öğrecinin birden fazla izin talebi vardır
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    //Bir öğrencinin birden fazla ödemesi vardır
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
