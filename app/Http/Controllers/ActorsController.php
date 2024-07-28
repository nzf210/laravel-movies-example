<?php

namespace App\Http\Controllers;

use App\Models\ActorsViewModel;
use App\Models\ActorViewModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

define('GET_API', 'https://api.themoviedb.org/3/person/');
class ActorsController extends Controller
{
    public function index($page = 1)
    {
        Log::info( $page);
        try {
            abort_if($page > 500, 204);
            $popularActors = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/person/popular?page=".$page)
            ->json()['results'];
            $viewModel = new ActorsViewModel($popularActors, $page);
            return Inertia::render('Actors/Index', ['data' => $viewModel]);
        } catch (\Throwable $th) {
            Log::info( 'Index actor error: '.$th);
            return Inertia::render('Actors/Index', ['data' => []]);
        }
        
    }

    public function show($id)
    {
        try {
            $actor = Http::withToken(config('services.tmdb.token'))
            ->get(GET_API.$id)
            ->json();

            $social = Http::withToken(config('services.tmdb.token'))
                ->get(GET_API.$id.'/external_ids')
                ->json();

            $credits = Http::withToken(config('services.tmdb.token'))
                ->get(GET_API.$id.'/combined_credits')
                ->json();

            $viewModel = new ActorViewModel($actor, $social, $credits);

            return Inertia::render('Actors/Show', ['data' => $viewModel]);
        } catch (\Throwable $th) {
            Log::info( 'Show actor error: '.$th);
            return Inertia::render('Actors/Show', ['data' => []]);
        }
    }
}
