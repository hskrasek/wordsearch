<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SolveGame as SolveGameRequest;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;

class SolveGame extends Controller
{
    public function __invoke(SolveGameRequest $request, Game $game): RedirectResponse
    {
        $word = $request->word();

        $wordCoordinates = $game->grid->getWordCoordinates()[$word->text];

        $solved = false;

        foreach ($wordCoordinates as $index => $wordCoordinate) {
            $solved = $solved || $wordCoordinate === $request->coordinates()[$index];
        }

        if (!$solved) {
            return redirect()->route('game.play', compact('game'))->withErrors(['coordinates' => 'Incorrect coordinates for word']);
        }

        $game->words()->updateExistingPivot($word, ['found' => true, 'found_at' => Carbon::now()]);

        return to_route('game.play', compact('game'));
    }
}
