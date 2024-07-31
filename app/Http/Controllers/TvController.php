<?php

namespace App\Http\Controllers;

use App\Models\TvShowViewModel;
use App\Models\TvViewModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class TvController extends Controller
{
    public function index($page=1)
    {
        try {
            $popularTv = Http::withToken(TOKEN)
            ->get(URL.'tv/popular')
            ->json()['results'];

            $topRatedTv = Http::withToken(TOKEN)
                ->get(URL."tv/top_rated?page={$page}")
                ->json()['results'];

            $genres = Http::withToken(TOKEN)
                ->get(URL.'genre/tv/list')
                ->json()['genres'];

            $viewModel = new TvViewModel(
                $popularTv,
                $topRatedTv,
                $genres,
            );

            return Inertia::render('Tv/Index',['data' => $viewModel]);
        } catch (\Throwable $th) {
            Log::info( "Tv/Index: {$th}");
            return Inertia::render('Tv/Index',['data' => []]);
        }
        
    }

    public function show($id)
    {
        $tvshow = Http::withToken(config('services.tmdb.token'))
            ->get(URL."tv/{$id}?append_to_response=credits,videos,images")
            ->json();

        $viewModel = new TvShowViewModel($tvshow);

        return Inertia::render('Tv/Show',['data' => $viewModel]);

    }
}
