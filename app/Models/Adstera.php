<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adstera extends Model
{
    use HasFactory;
    protected $table = 'adstera';
    protected $fillable = [
        'adstera_url'
    ];
}
