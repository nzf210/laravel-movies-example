<?php

namespace App\Http\Controllers;

use App\Models\MoviesViewModel;
use App\Models\MovieViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class MoviesController extends Controller
{
    public function index()
    {
        $popularMovies = Http
        ::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/popular')
            ->json()['results'];

        $nowPlayingMovies = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/now_playing')
            ->json()['results'];

        $genres = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/genre/movie/list')
            ->json()['genres'];

        $viewModel = new MoviesViewModel(
            $popularMovies,
            $nowPlayingMovies,
            $genres,
        );
        return Inertia::render('Welcome',['data' => $viewModel]);
    }

    public function show($id)
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id.'?append_to_response=credits,videos,images')
            ->json();

        $viewModel = new MovieViewModel($movie);

        return Inertia::render('Movie/MovieDetail',['data' => $viewModel]);
    }
}
