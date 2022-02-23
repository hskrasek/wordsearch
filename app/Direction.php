<?php

declare(strict_types=1);

namespace App;

enum Direction
{
    case Forward;
    case Backward;
    case Up;
    case Down;

    public function toCardinality(): array
    {
        return match ($this) {
            self::Forward => [1, 0],
            self::Backward => [-1, 0],
            self::Up => [-1, -1],
            self::Down => [1, 1]
        };
    }
}
