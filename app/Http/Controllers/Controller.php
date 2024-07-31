<?php

namespace App\Http\Controllers;

define("URL",config('services.tmdb.url'));
define("TOKEN",config('services.tmdb.token'));
define("TOKENB","Bearer ".config('services.tmdb.token'));
define("APPJSON",'application/json');
abstract class Controller
{
    //
}
