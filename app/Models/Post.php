<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu', 'dateCreation','SousCategorieID'
    ];

    public function sousCategorie()
    {
        return $this->belongsTo(SousCategorie::class,"SousCategorieID");
    }
}
