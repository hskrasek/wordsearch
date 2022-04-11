<?php

use App\Game\Difficulty;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Word;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('games/{game}', function (Request $request, Game $game) {
    return new GameResource($game);
});

Route::get('games/{game}/stats', function (Request $request, Game $game) {
    /** @var Collection<Carbon> $timings */
    $timings = $game->words->map(fn(Word $word) => Carbon::parse($word->session->found_at));
    $took    = $timings->last()->diffInSeconds($game->created_at);

    return [
        'difficulty'          => $game->difficulty->name,
        'total_words'         => $game->words->count(),
        'completed'           => true,
        'took'                => $took,
        'started_at'          => $game->created_at,
        'first_word_found_at' => $timings->first(),
        'finished_at'         => $timings->last(),
    ];
});

Route::post('games', function (Request $request) {
    $difficulty = Difficulty::from($request->input('difficulty'));

    //TODO: Grid creation is fucked up, not sure why. Might need to revert the entire Grid class changes
    //TODO: Need to improve the word selection based off of the difficulty, current approach I think is broken

    $words = Word::query()
        ->whereBetween('length', [$difficulty->gridSize() - 2, $difficulty->gridSize()])
        ->inRandomOrder()
        ->limit($difficulty->wordCount())
        ->get();

    $game = Game::start($difficulty, $words);

    return new GameResource($game);
});
