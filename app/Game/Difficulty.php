<?php

declare(strict_types=1);

namespace App\Game;

use App\Concerns\Game\Randomable;

enum Difficulty: int
{
    use Randomable;

    case Easy = 0;
    case Medium = 1;
    case Hard = 2;
    case Insane = 3;

    public function wordCount(): int
    {
        return match ($this) {
            self::Easy => 4,
            self::Medium => 9,
            self::Hard => 16,
            self::Insane => 25,
        };
    }

    public function gridSize(): int
    {
        return match ($this) {
            self::Easy => 5,
            self::Medium => 8,
            self::Hard => 13,
            self::Insane => 34,
        };
    }
}
