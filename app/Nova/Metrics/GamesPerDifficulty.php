<?php

namespace App\Nova\Metrics;

use App\Game\Difficulty;
use App\Models\Game;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class GamesPerDifficulty extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     *
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Game::class, 'difficulty')
            ->label(fn (int $value) => Difficulty::from($value)->name);
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     */
    public function uriKey(): string
    {
        return 'games-per-difficulty';
    }
}
