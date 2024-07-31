<?php

use App\Http\Controllers\ActorsController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\SearchDropdown;
use App\Http\Controllers\TvController;
use Illuminate\Support\Facades\Route;

define('PROFILE', 'profile');


Route::get('/tv', [TvController::class,'index'])->name('tv.index');
Route::get('/tv/{id}', [TvController::class,'show'])->name('tv.show');
Route::get('/tv/load/{page?}', [TvController::class,'index'])->name('tv.load');

Route::get('/actors', [ActorsController::class, 'index'])->name('actors.index');
Route::get('/actors/page/{page?}', [ActorsController::class, 'index'])->name('actors.page');

Route::get('/actors/{id}', [ActorsController::class, 'show'])->name('actors.show');
Route::get('/search/{qry?}', [SearchDropdown::class, 'index'])->name('movie.search');

Route::get('/load/{page?}', [MoviesController::class, 'index'])->name('movies.load');
Route::get('/movies/{id}', [MoviesController::class, 'show'])->name('movies.show');
Route::get('/', [MoviesController::class, 'index'])->name('movies.index');

require_once __DIR__.'/auth.php';
