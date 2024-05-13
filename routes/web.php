<?php

use App\Http\Controllers\CreateGame;
use App\Http\Controllers\Game;
use App\Http\Controllers\Home;
use App\Http\Controllers\SolveGame;
use App\Models\Game as GameModel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', Home::class)->name('home');

Route::post('/game', CreateGame::class)
    ->middleware('auth:sanctum')
    ->name('game.create')
    ->can('create', GameModel::class);

Route::get('/game/{game}', Game::class)
    ->middleware('auth:sanctum')
    ->can('view', 'game')
    ->name('game.play');

Route::post('/game/{game}/solve', SolveGame::class)
    ->middleware('auth:sanctum')
    ->name('game.solve')
    ->can('update', 'game');

require __DIR__.'/auth.php';
