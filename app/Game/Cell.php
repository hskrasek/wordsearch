<?php

declare(strict_types=1);

namespace App\Game;

use Illuminate\Contracts\Support\Arrayable;

final class Cell implements Arrayable, \JsonSerializable
{
    private bool $found = false;

    public function __construct(private string $letter)
    {
    }

    public function find(): void
    {
        $this->found = true;
    }

    public function toArray()
    {
        return [
            'letter' => $this->letter,
            'found'  => $this->found,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
