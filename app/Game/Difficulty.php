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

    public function minimumWordLength(): int
    {
        return match ($this) {
            self::Easy => 6,
            self::Medium => 5,
            self::Hard => 4,
            self::Insane => 2,
        };
    }

    public function wordCount(): int
    {
        return match ($this) {
            self::Easy => 6,
            self::Medium => 10,
            self::Hard => 16,
            self::Insane => 24,
        };
    }

    public function gridSize(): int
    {
        return 16;
    }
}
