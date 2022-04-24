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

        $averageSecondsBetweenWords = round($timings->prepend($game->created_at)->pipe(function (Collection $timings) {
            return $timings->map(function (Carbon $timestamp, int $key) use ($timings) {
                /** @var Carbon|null $nextTimestamp */
                $nextTimestamp = $timings->get(($key + 1));

                if ($nextTimestamp === null) {
                    return 0; //TODO: Refactor out magic number
                }

                return $nextTimestamp->diffInSeconds($timestamp);
            });
        })->avg());

        return \response()->json(
            [
                'difficulty'            => $game->difficulty->name,
                'total_words'           => $game->words->count(),
                'completed'             => true,
                'took'                  => $took,
                'average_between_words' => $averageSecondsBetweenWords,
                'started_at'            => $game->created_at,
                'first_word_found_at'   => $timings->first(),
                'finished_at'           => $timings->last(),
            ]
        );
    }
}
