<?php

declare(strict_types=1);

namespace App\Game;

use App\Concerns\Game\Randomable;
use App\Game\Attributes\Directions;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;

/**
 * @method array directions
 */
#[Meta(Directions::class)]
enum Difficulty: int
{
    use Metadata;
    use Randomable;

    #[Directions([Direction::North, Direction::East])]
    case Easy = 0;

    #[Directions([Direction::North, Direction::East, Direction::South, Direction::West])]
    case Medium = 1;

    #[Directions([
        Direction::North, Direction::East, Direction::South, Direction::West, Direction::NorthEast, Direction::SouthWest,
    ])]
    case Hard = 2;

    #[Directions([
        Direction::North, Direction::East, Direction::South, Direction::West, Direction::NorthEast,
        Direction::SouthEast, Direction::SouthWest, Direction::NorthWest,
    ])]
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
