<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'user_id', 'tc_no', 'phone', 'email',
        'department', 'shift_start', 'shift_end', 'start_date'
    ];

    //Bir personelin bir kullanıcı hesabı vardır
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dutySchedules()
    {
        return $this->hasMany(DutySchedule::class);
    }

    public function cleaningTasks()
    {
        return $this->hasMany(CleaningTask::class);
    }
}
