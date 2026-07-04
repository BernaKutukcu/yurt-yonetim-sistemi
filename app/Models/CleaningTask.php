<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleaningTask extends Model
{
    protected $fillable = [
        'staff_id', 'task_date', 'location', 'status', 'note'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
