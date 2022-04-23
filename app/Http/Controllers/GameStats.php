<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Word;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class GameStats extends Controller
{
    public function __invoke(Request $request, Game $game): Response|JsonResponse
    {
        /** @var Collection<Carbon> $timings */
        $timings = $game->words->map(fn(Word $word) => Carbon::parse($word->session->found_at))->sort();
        $took    = $timings->last()->diffInSeconds($game->created_at);

        return \response()->json(
            [
                'difficulty'          => $game->difficulty->name,
                'total_words'         => $game->words->count(),
                'completed'           => true,
                'took'                => $took,
                'started_at'          => $game->created_at,
                'first_word_found_at' => $timings->first(),
                'finished_at'         => $timings->last(),
            ]
        );
    }
}
