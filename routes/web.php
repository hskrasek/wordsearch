<?php

use App\Http\Controllers\CreateGame;
use App\Http\Controllers\Game;
use App\Http\Controllers\Home;
use App\Http\Controllers\SolveGame;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    ->can('create');

Route::get('/game/{game}', Game::class)
    ->middleware('auth:sanctum')
    ->name('game.play')
    ->can('view');

Route::post('/game/{game}/solve', SolveGame::class)
    ->middleware('auth:sanctum')
    ->name('game.solve')
    ->can('update');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
