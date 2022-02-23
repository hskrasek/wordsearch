<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Str;

final class GameGrid
{
    private array $grid;

    public function __construct(private int $size)
    {
        $this->grid = array_fill(0, $this->size, array_fill(0, $this->size, '-'));
    }

    public function insertWord(string $word, Direction $direction): void
    {
        $wordLength = strlen($word);
        $letters    = Str::ucsplit($word);
        [$xCoordinates, $yCoordinates] = $this->randomizeCoordinates();
        $insertCoordinates = [];

        for ($i = 0; $i < $this->size; $i++) {
            // If we are iterating to a new set of x and y coordinates,
            // and we have left over coordinates from a previous attempt
            // to insert the word, we need to reset the insertion coordinates.
            if (!empty($insertCoordinates)) {
                $insertCoordinates = [];
            }

            $x = $xCoordinates[$i];
            $y = $yCoordinates[$i];

            $endX = $x + ($wordLength * $direction->toCardinality()[0]);
            $endY = $y + ($wordLength * $direction->toCardinality()[1]);

            foreach ($letters as $letter) {
                // The cell is not empty, and does not equal the letter
                if ((
                    $this->grid[$x][$y] !== '-' &&
                    $this->grid[$x][$y] !== $letter
                )) {
                    // Exit inserts, and try new coordinates
                    break;
                }

                // The starting cell does not provide enough room for
                // the word to be inserted into the grid
                if (
                    ($endX < 0 || $endY < 0) ||
                    ($endX > ($this->size -1) || $endY > ($this->size -1))
                ) {
                    // Exit inserts, and try new coordinates
                    break;
                }

                $insertCoordinates[] = [
                    $x,
                    $y
                ];

                $x += $direction->toCardinality()[0];
                $y += $direction->toCardinality()[1];
            }
        }

        // if at this point, word should either be insertable, or not.
        // if it couldn't be inserted, we will do something. TODO: Figure out something
        // if insertable, insert and return true
        if (count($insertCoordinates) < $wordLength) {
            return;
        }

        foreach ($insertCoordinates as $index => $coordinate) {
            $this->grid[$coordinate[0]][$coordinate[1]] = $letters[$index];
        }
    }

    public function __toString(): string
    {
        return array_reduce($this->grid, function (string $carry, array $row) {
            foreach ($row as $cell) {
                $carry .= " $cell ";
            }

            $carry .= PHP_EOL;

            return $carry;
        }, '');
    }

    /**
     * @return array{non-empty-array<int>, non-empty-array<int>}
     */
    private function randomizeCoordinates(): array
    {
        return array_map(
            function (array $coords) {
                shuffle($coords);

                return $coords;
            },
            [
                range(0, $this->size - 1),
                range(0, $this->size - 1),
            ]
        );
    }
}
