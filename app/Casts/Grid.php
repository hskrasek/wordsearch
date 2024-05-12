<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Database\Eloquent\Model;
use App\Game\Grid as GameGrid;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Grid implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): GameGrid
    {
        return GameGrid::fromArray(json_decode($attributes['grid'], true));
    }

    /**
     * Prepare the given value for storage.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof GameGrid) {
            throw new \InvalidArgumentException('The given value is not a Grid instance.');
        }

        return json_encode($value);
    }
}
