<?php

declare(strict_types=1);

namespace App\Game;

enum Direction: string
{
    case North = 'North';
    case NorthEast = 'North-East';
    case NorthWest = 'North-West';
    case East = 'East';
    case South = 'South';
    case SouthEast = 'South-East';
    case SouthWest = 'South-West';
    case West = 'West';

    public function toCardinality(): array
    {
        return match ($this) {
            self::North => [-1, 0],
            self::NorthEast => [-1, 1],
            self::NorthWest => [-1, -1],
            self::East => [0, 1],
            self::South => [1, 0],
            self::SouthEast => [1, 1],
            self::SouthWest => [1, -1],
            self::West => [0, -1],
        };
    }

    public static function random(): self
    {
        return self::cases()[array_rand(self::cases())];
    }
}
