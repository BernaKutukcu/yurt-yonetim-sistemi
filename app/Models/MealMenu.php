<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealMenu extends Model
{
    protected $fillable = [
        'date', 'breakfast', 'dinner', 'is_served'
    ];

    //Bir menünün birden fazla yemek kartı var(yani öğrenci parmak izi)
    public function mealCards()
    {
        return $this->hasMany(MealCard::class);
    }
}
