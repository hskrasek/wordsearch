<?php

declare(strict_types=1);

namespace App\Exceptions\Game;

use Exception;
use JetBrains\PhpStorm\Pure;

class GridException extends Exception
{
    #[Pure]
    public static function unableToInsertWord(string $word): static
    {
        return new static(
            message: "Unable to insert word into grid: $word"
        );
    }

    #[Pure]
    public static function wordNotInGrid(string $word): static
    {
        return new static(
            message: "$word is not within the grid"
        );
    }
}
