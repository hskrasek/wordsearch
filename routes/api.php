<?php

use App\Http\Controllers\CreateGame;
use App\Http\Controllers\Game;
use App\Http\Controllers\GameStats;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('games.info')
    ->middleware('auth:sanctum')
    ->get('games/{game}', Game::class);

Route::name('game.stats')
    ->middleware('auth:sanctum')
    ->get('games/{game}/stats', GameStats::class);

Route::name('game.create')
    ->middleware('auth:sanctum')
    ->post('games', CreateGame::class);

Route::name('user.info')
    ->middleware('auth:sanctum')
    ->get('users/{user}', User::class);
