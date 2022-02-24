<?php

use App\Game\Direction;
use App\Game\Grid;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

// beforeAll(function () {
//
// });

it('can insert a word into the grid in any direction', function (string $direction) {
    $word = 'APPLE';

    $grid = new Grid(15);
    $grid->insertWord($word, Direction::from($direction));

    expect((string)$grid)->toBeString()->toContain(...Str::ucsplit($word));
})->with('directions');

it('can insert multiple words in any direction', function () {
    $grid = new Grid(15);
    $directions = Direction::cases();
    shuffle($directions);

    $grid->insertWord('APPLE', Arr::random($directions));

    $grid->insertWord('UNMARRED', Arr::random($directions));

    $grid->insertWord('OXYTOCIN', Arr::random($directions));

    $grid->insertWord('HALO', Arr::random($directions));

    $gridString = (string)$grid;

    expect($gridString)->toBeString()->toContain(...Str::ucsplit('APPLE'));
    expect($gridString)->toBeString()->toContain(...Str::ucsplit('UNMARRED'));
    expect($gridString)->toBeString()->toContain(...Str::ucsplit('OXYTOCIN'));
    expect($gridString)->toBeString()->toContain(...Str::ucsplit('HALO'));
});
