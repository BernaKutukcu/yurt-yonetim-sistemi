<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id', 'amount', 'payment_date',
        'due_date', 'status'
    ];

    //Bu ödeme bir öğrenciye aittir
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
