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

    public static function fromName(string $name): ?Difficulty
    {
        return match (true) {
            self::Easy->name === $name => self::Easy,
            self::Medium->name === $name => self::Medium,
            self::Hard->name === $name => self::Hard,
            self::Insane->name === $name => self::Insane,
            default => null
        };
    }

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
            self::Easy => 10,
            self::Medium => 16,
            self::Hard => 26,
            self::Insane => 34,
        };
    }
}
