<?php

namespace App\Game;

use App\Models\Game;
use App\Models\Word;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Stats
{
    /**
     * @param  Game  $game
     * @return array
     */
    public function calculate(Game $game): array
    {
        return [
            $this->difficultyStats($game),
            $this->completionTimeStats($game),
            $this->averageSecondsFindingWordsStats($game),
            ...$this->wordsStats($game),
            $this->firstWordFoundStats($game),
        ];
    }

    private function difficultyStats(Game $game): array
    {
        return $this->makeStat(
            name: 'Difficulty',
            stat: $game->difficulty->name,
            change: $game->difficulty->value + 1,
        );
    }

    private function completionTimeStats(Game $game): array
    {
        /** @var Collection<Carbon> $timings */
        $timings = $game->words->map(fn(Word $word) => Carbon::parse($word->session->found_at))->sort();

        return $this->makeStat(
            name: 'Completion Time',
            stat: $timings->last()->longAbsoluteDiffForHumans($game->created_at),
            change: $timings->last()->diffInSeconds($game->created_at),
        );
    }

    private function averageSecondsFindingWordsStats(Game $game): array
    {
        /** @var Collection<Carbon> $timings */
        $timings = $game->words->map(fn(Word $word) => Carbon::parse($word->session->found_at))->sort();

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

        return $this->makeStat(
            name: 'Average Time to Find A Word',
            stat: Carbon::now()->subSeconds($averageSecondsBetweenWords)->longAbsoluteDiffForHumans(),
            change: $averageSecondsBetweenWords,
        );
    }

    private function wordsStats(Game $game): array
    {
        /** @var Collection $wordStats */
        $wordStats = DB::query()->select(['w.text as word', 'found_at'])
            ->selectRaw('lag(found_at) over (PARTITION BY game_id) as previously_found_at')
            ->from('game_word', 'gw')
            ->join('words as w', 'w.id', '=', 'gw.word_id')
            ->whereNotNull('gw.found_at')
            ->where('game_id', $game->id)
            ->get()
            ->mapWithKeys(fn(object $word) => [$word->word => $word])
            ->pipe(function (Collection $words) use ($game) {
                $word = $words->first();

                $word->previously_found_at = $game->created_at->toDateTimeString();

                $words->put($word->word, $word);

                return $words;
            })
            ->map(function (object $word) {
                $word->found_at = Carbon::parse($word->found_at);
                $word->previously_found_at = Carbon::parse($word->previously_found_at);

                $word->found_in_seconds = $word->found_at->diffInSeconds($word->previously_found_at);

                return $word;
            })
            ->sortBy(fn(object $word) => $word->found_in_seconds);

        return [
            $this->makeStat(
                name: 'Quickest Word Found',
                stat: $wordStats->first()->word,
                change: Carbon::now()->subSeconds($wordStats->first()->found_in_seconds)->longAbsoluteDiffForHumans(),
            ),
            $this->makeStat(
                name: 'Slowest Word Found',
                stat: $wordStats->last()->word,
                change: Carbon::now()->subSeconds($wordStats->last()->found_in_seconds)->longAbsoluteDiffForHumans(),
            ),
        ];
    }

    private function firstWordFoundStats(Game $game): array
    {
        /** @var Collection<Carbon> $timings */
        $timings = $game->words->map(fn(Word $word) => Carbon::parse($word->session->found_at))->sort();

        return $this->makeStat(
            name: 'First Word Found',
            stat: $game->words->sortBy(fn (Word $word) => $word->session->found_at)->first()->text,
            change: $timings->first()->longAbsoluteDiffForHumans($game->created_at),
        );
    }

    private function makeStat(string $name, string $stat, string $change): array
    {
        return compact('name', 'stat', 'change');
    }
}
