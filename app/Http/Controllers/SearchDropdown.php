<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class SearchDropdown extends Controller
{
    public function index($qry='')
    {
        $searchResults = [];
        try {
            if (strlen($qry) >= 2) {
                $searchResults = Http::withToken(config('services.tmdb.token'))
                    ->get('https://api.themoviedb.org/3/search/movie?query='.$qry)
                    ->json()['results'];
            }
            
        session(['search' =>  collect($searchResults)->take(7) ?? []]);
        return back()->with('search',  collect($searchResults)->take(7));
        } catch (\Throwable $th) {
            return back()->with(['search' =>  []]);
        }
    }
}
