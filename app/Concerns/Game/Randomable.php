<?php

declare(strict_types=1);

namespace App\Concerns\Game;

trait Randomable
{
    public static function random(): self
    {
        return self::cases()[array_rand(self::cases())];
    }
}
