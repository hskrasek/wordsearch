<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateGame as CreateGameRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Word;
use Illuminate\Http\RedirectResponse;

class CreateGame extends Controller
{
    public function __invoke(CreateGameRequest $request): RedirectResponse|GameResource
    {
        $difficulty = $request->difficulty();

        $words = Word::forDifficulty($difficulty)
            ->get();

        $game = Game::start($difficulty, $words);

        if ($request->wantsJson()) {
            return new GameResource($game);
        }

        return redirect()->route('game.play', compact('game'));
    }
}
