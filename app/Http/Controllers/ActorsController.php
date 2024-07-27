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
        abort_if($page > 500, 204);
        Log::info( $page);
        $popularActors = Http::withToken(config('services.tmdb.token'))
            ->get("https://api.themoviedb.org/3/person/popular?page=".$page)
            ->json()['results'];

        $viewModel = new ActorsViewModel($popularActors, $page);
        return Inertia::render('Actors/Index', ['data' => $viewModel]);
    }

    public function show($id)
    {
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

    }
}
