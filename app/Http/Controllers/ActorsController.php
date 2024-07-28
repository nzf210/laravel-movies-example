<?php

namespace App\Http\Controllers;

use App\Models\ActorsViewModel;
use App\Models\ActorViewModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

define('GET_API', 'https://api.themoviedb.org/3/person/');
define('ACTOR','Actors/Index');
class ActorsController extends Controller
{
    public function index($page = 1)
    {
        Log::info("Request For Popular Actors Page {$page}");
        $client = new \GuzzleHttp\Client();
        try {
            abort_if($page > 500, 204);
            $token = config('services.tmdb.token');
            $popularActor = $client->request('GET', GET_API."popular?page={$page}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'accept' => 'application/json',
                ],
            ]);
            $responseBody = $popularActor->getBody()->getContents();
            $statusCode = $popularActor->getStatusCode();
            if($statusCode == 200) {
                $popularActors = json_decode($responseBody, true);
                $viewModel = new ActorsViewModel($popularActors['results'], $page);
                $data = $viewModel;
            } else {
                $data = [];   
            }
            return Inertia::render(ACTOR, ['data' => $data]);
        } catch (\Throwable $th) {
            Log::info( "Index actor error: {$th}");
            return Inertia::render(ACTOR, ['data' => []]);
        }
        
    }

    public function show($id)
    {
        try {
            $actor = Http::withToken(config('services.tmdb.token'))
            ->get(GET_API.$id)
            ->json();

            $social = Http::withToken(config('services.tmdb.token'))
                ->get(GET_API."{$id}/external_ids")
                ->json();

            $credits = Http::withToken(config('services.tmdb.token'))
                ->get(GET_API."{$id}/combined_credits")
                ->json();

            $viewModel = new ActorViewModel($actor, $social, $credits);

            return Inertia::render('Actors/Show', ['data' => $viewModel]);
        } catch (\Throwable $th) {
            Log::info( "Show actor error: {$th}");
            return Inertia::render('Actors/Show', ['data' => []]);
        }
    }
}
