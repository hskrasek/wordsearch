<?php

declare(strict_types=1);

namespace App\Game;

use App\Exceptions\Game\GridException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

use function retry;

final class Grid implements \ArrayAccess, Arrayable, \JsonSerializable
{
    private const PLACEHOLDER = '-';

    private array $grid;

    private array $wordCoordinates = [];

    public function __construct(private int $size)
    {
        $this->grid = array_fill(0, $this->size, array_fill(0, $this->size, self::PLACEHOLDER));
    }

    public function insertWord(string $word, Direction $direction): void
    {
        $wordLength        = strlen($word);
        $letters           = Str::ucsplit($word);
        $insertCoordinates = [];

        retry(100, function (int $attempts) use ($wordLength, $letters, &$insertCoordinates, &$direction) {
            [$xCoordinates, $yCoordinates] = $this->randomizeCoordinates();

            if ($attempts > 0 && 100 % $attempts === 0) {
                $direction = Direction::random();
            }

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
                    if (
                        (
                        $this->grid[$x][$y] !== '-' &&
                        $this->grid[$x][$y] !== $letter
                        )
                    ) {
                        // Exit inserts, and try new coordinates
                        break;
                    }

                    // The starting cell does not provide enough room for
                    // the word to be inserted into the grid
                    if (
                        ($endX < 0 || $endY < 0) ||
                        ($endX > ($this->size - 1) || $endY > ($this->size - 1))
                    ) {
                        // Exit inserts, and try new coordinates
                        break;
                    }

                    $insertCoordinates[] = [
                        $x,
                        $y,
                    ];

                    $x += $direction->toCardinality()[0];
                    $y += $direction->toCardinality()[1];
                }
            }

            // if at this point, word should either be insertable, or not.
            // if it couldn't be inserted, we will do something. TODO: Figure out something
            // if insertable, insert and return true
            if (count($insertCoordinates) < $wordLength) {
                throw GridException::unableToInsertWord(implode('', $letters));
            }
        });

        foreach ($insertCoordinates as $index => $coordinate) {
            $this->grid[$coordinate[0]][$coordinate[1]] = $letters[$index];
        }

        $this->wordCoordinates[$word] = $insertCoordinates;
    }

    public function finalize(): void
    {
        for ($x = 0; $x < $this->size; $x++) {
            for ($y = 0; $y < $this->size; $y++) {
                if ($this->grid[$x][$y] !== self::PLACEHOLDER) {
                    continue;
                }

                $this->grid[$x][$y] = chr(random_int(65, 90));
            }
        }
        $this->grid = iterator_to_array(
            collect($this->grid)
                ->mapInto(Collection::class)
                ->map(fn(Collection $row) => $row->mapInto(Cell::class))
                ->getIterator()
        );
    }

    /**
     * @param string $word
     * @param array<array> $coordinates
     *
     * @return bool
     * @throws \Exception
     */
    public function findWord(string $word, array $coordinates): bool
    {
        if (!array_key_exists($word, $this->wordCoordinates)) {
            throw GridException::wordNotInGrid($word);
        }

        $wordCoordinates = $this->wordCoordinates[$word];

        foreach ($coordinates as $coordinate) {
            if (!$this->doesCoordinateExist($wordCoordinates, $coordinate)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array{string: array}
     */
    public function getWordCoordinates(): array
    {
        return $this->wordCoordinates;
    }

    public function size(): int
    {
        return $this->size;
    }

    private function doesCoordinateExist(array $wordCoordinates, array $coordinate): bool
    {
        $found = false;

        foreach ($wordCoordinates as $wordCoordinate) {
            $found = $found || $wordCoordinate === $coordinate;
        }

        return $found;
    }

    public function __toString(): string
    {
        $rowLength = 0;
        $grid      = array_reduce($this->grid, function (string $carry, array $row) use (&$rowLength) {
            $carry .= '|';

            foreach ($row as $cell) {
                $carry .= " $cell ";
            }

            $carry .= '|' . PHP_EOL;

            if ($rowLength === 0) {
                $rowLength = strlen($carry);
            }

            return $carry;
        }, '');

        $border = str_repeat('-', $rowLength - 1) . PHP_EOL;

        return $border . $grid . $border;
    }

    /**
     * @return array{non-empty-array<int>, non-empty-array<int>}
     */
    private function randomizeCoordinates(): array
    {
        return array_map(
            static function (array $coords) {
                shuffle($coords);

                return $coords;
            },
            [
                range(0, $this->size - 1),
                range(0, $this->size - 1),
            ]
        );
    }

    /**
     * @return array<int, array<int, Cell>
     */
    public function toArray()
    {
        return $this->grid;
    }

    public static function fromArray(array $array): self
    {
        return tap(new self(count($array['grid'])), function (self $grid) use ($array) {
            $grid->grid            = self::unserializeGrid($array['grid']);
            $grid->wordCoordinates = $array['word_coordinates'];
            $grid->size            = count($array['grid']);
        });
    }

    /**
     * @param array $grid
     *
     * @return array<int, array<int, Cell>
     */
    private static function unserializeGrid(array $grid): array
    {
        foreach ($grid as $x => $row) {
            foreach ($row as $y => $cell) {
                $grid[$x][$y] = new Cell($cell['letter']);

                if ($cell['found']) {
                    $grid[$x][$y]->find();
                }
            }
        }

        return $grid;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'grid'             => $this->toArray(),
            'word_coordinates' => $this->getWordCoordinates(),
        ];
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->grid);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->grid[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \LogicException("Grids cannot be modified in this manner");
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \LogicException("Grids cannot be modified in this manner");
    }
}
