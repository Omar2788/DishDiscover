<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCategorie extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomscategorie'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class,"categorieID");
    }

    public function post()
    {
        return $this->hasMany(Post::class ,"SousCategorieID");
    }

}
