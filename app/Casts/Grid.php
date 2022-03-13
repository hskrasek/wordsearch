<?php declare(strict_types=1);

namespace App\Casts;

use App\Game\Grid as GameGrid;
use App\Models\Game;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Grid implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return \App\Game\Grid
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return GameGrid::fromArray(json_decode($attributes['grid'], true));
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  GameGrid  $value
     * @param  array  $attributes
     * @return array
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (!$value instanceof GameGrid) {
            throw new \InvalidArgumentException("The given value is not a Grid instance.");
        }

        return json_encode($value);
    }
}
