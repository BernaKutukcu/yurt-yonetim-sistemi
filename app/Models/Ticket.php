<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'student_id', 'type', 'title', 'description', 'status', 'admin_note'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
