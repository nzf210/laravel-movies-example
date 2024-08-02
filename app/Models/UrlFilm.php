<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlFilm extends Model
{
    use HasFactory;
    protected $table = 'films';
    protected $fillable = [
        'film_id',
        'film_url',
    ];
}
