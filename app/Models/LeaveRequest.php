<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'student_id', 'start_date', 'end_date',
        'address', 'description', 'status', 'approved_by'
    ];

    //Bu izin talebi bir öğrenciye aittir
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    //Bu izni onaylayan kullanıcıdır
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
