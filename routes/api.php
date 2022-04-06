<?php

use App\Game\Difficulty;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Word;
use Carbon\Carbon;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('games/{game}', function (Request $request, Game $game) {
    return new GameResource($game);
});

Route::post('games', function (Request $request) {
    $difficulty = Difficulty::from($request->input('difficulty'));

    //TODO: Grid creation is fucked up, not sure why. Might need to revert the entire Grid class changes
    //TODO: Need to improve the word selection based off of the difficulty, current approach I think is broken

    $words = Word::query()
        ->whereBetween('length', [$difficulty->gridSize()-2, $difficulty->gridSize()])
        ->inRandomOrder()
        ->limit($difficulty->wordCount())
        ->get();

    $game = Game::start($difficulty, $words);

    return new GameResource($game);
});

Route::post('/games/{game}/solve', function (Request $request, Game $game) {
    if (!$game->words->contains($word = Word::where('text', $request->word)->first())) {
        return \response([], 422);
    }

    $wordCoordinates = $game->grid->getWordCoordinates()[$word->text];

    $solved = false;

    foreach ($wordCoordinates as $index => $wordCoordinate) {
        $solved = $solved || $wordCoordinate === $request->coordinates[$index];
    }

    if (!$solved) {
        return \response([], 422);
    }

    $game->words()->updateExistingPivot($word, ['found' => true, 'found_at' => Carbon::now()]);

    return Redirect::route('game.play', [$game->uuid]);
});
