<?php

use App\Http\Controllers\CreateGame;
use App\Http\Controllers\Game;
use App\Http\Controllers\GameStats;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->name('user.info')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(Game::class)
    ->name('games.info')
    ->get('games/{game}');

Route::controller(GameStats::class)
    ->name('game.stats')
    ->get('games/{game}/stats');

Route::controller(CreateGame::class)
    ->name('game.create')
    ->post('games');
