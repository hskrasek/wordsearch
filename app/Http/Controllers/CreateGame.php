<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Game\Difficulty;
use App\Models\Game;
use App\Models\Word;
use Illuminate\Http\Request;

class CreateGame extends Controller
{
    public function __invoke(Request $request)
    {
        $difficulty = Difficulty::from((int)$request->input('difficulty'));

        $words = Word::query()
            ->whereBetween('length', [4, $difficulty->gridSize()])
            ->inRandomOrder()
            ->limit($difficulty->wordCount())
            ->get();

        $game = Game::start($difficulty, $words);

        return redirect()->route('game.play', compact('game'));
    }
}
