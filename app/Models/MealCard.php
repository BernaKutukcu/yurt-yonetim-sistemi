<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealCard extends Model
{
    protected $fillable = [
        'student_id', 'meal_menu_id', 'meal_type', 'is_eaten'
    ];

    //Bu kart bir öğrenciye aittir
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    //Bu kart bir menüye aittir
    public function mealMenu()
    {
        return $this->belongsTo(MealMenu::class);
    }
}
