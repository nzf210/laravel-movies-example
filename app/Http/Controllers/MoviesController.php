<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdsteraResource;
use App\Models\Adstera;
use App\Models\MoviesViewModel;
use App\Models\MovieViewModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;


class MoviesController extends Controller
{
    public function index($page = 1)
    {
        $client = new \GuzzleHttp\Client();

        try {
            abort_if($page > 5000, 204);            
            $popularMovies = $client->request('GET', URL."movie/popular", [
                    'headers' => [
                        'Authorization' => TOKENB,
                        'accept' => APPJSON,
                    ],
            ]);
            
            $genres = $client->request('GET', URL."genre/movie/list", [
                'headers' => [
                    'Authorization' => TOKENB,
                    'accept' => APPJSON,
                ],
            ]);

            $nowPlayingMovies = $client->request('GET', URL."movie/now_playing?page={$page}", [
                'headers' => [
                    'Authorization' => TOKENB,
                    'accept' => APPJSON,
                ],
            ]);
            

            $resPopularMovies = $popularMovies->getBody()->getContents();
            $resNowPlayingMovies = $nowPlayingMovies->getBody()->getContents();
            $resGenres = $genres->getBody()->getContents();

            $popularMovie = json_decode($resPopularMovies, true);
            $nowPlayingMovie = json_decode($resNowPlayingMovies, true);
            $genre = json_decode($resGenres, true); 

            $viewModel = new MoviesViewModel(
                $popularMovie['results'],
                $nowPlayingMovie['results'],
                $genre['genres'],
            );
            return Inertia::render('Welcome',['data' => $viewModel]);
    } catch (\Throwable $th) {
            $viewModel = [
                'popularMovies' => '',
                'nowPlayingMovies' => '',
                'genres' => '',
            ];
            return Inertia::render('Welcome',['data' => $viewModel]);
        }
    }


    public function show($id)
    {
        try {
            $adstera = Adstera::get();
            $movie = Http::withToken(TOKEN)->get(URL."movie/{$id}?append_to_response=credits,videos,images")->json();
            $viewModel = new MovieViewModel($movie);
            return Inertia::render('Movie/MovieDetail',['data' => $viewModel, 'ads' => AdsteraResource::collection($adstera)]);
        } catch (\Throwable $th) {
            Log::info("Error Show Movies {$th}");
            $viewModel = new MovieViewModel([]);
            return Inertia::render('Movie/MovieDetail',['data' => [], 'ads' => []]);
        }
    }
}
