<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryLog extends Model
{
    protected $fillable = [
        'student_id', 'entry_time', 'exit_time', 'is_late'
    ];

    //Bu kayıt bir öğrenciye aittir
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
