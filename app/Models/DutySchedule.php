<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DutySchedule extends Model
{
    protected $fillable = [
        'staff_id', 'duty_date', 'start_time', 'end_time', 'location', 'is_done'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
