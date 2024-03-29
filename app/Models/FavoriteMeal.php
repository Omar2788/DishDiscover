<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteMeal extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'meal_id',
    ];
   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }
}