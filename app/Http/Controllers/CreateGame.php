<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateGame as CreateGameRequest;
use App\Models\Game;
use App\Models\Word;
use Illuminate\Http\RedirectResponse;

class CreateGame extends Controller
{
    public function __invoke(CreateGameRequest $request): RedirectResponse
    {
        $difficulty = $request->difficulty();

        $words = Word::difficulty($difficulty)
            ->inRandomOrder()
            ->get();

        $game = Game::start($difficulty, $words);

        return redirect()->route('game.play', compact('game'));
    }
}
