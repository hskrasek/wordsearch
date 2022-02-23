<?php

use App\Direction;
use App\GameGrid;
use Illuminate\Support\Str;

// beforeAll(function () {
//
// });

it('can insert a word into the grid', function () {
    $word = 'APPLE';

    $grid = new GameGrid(15);
    $grid->insertWord($word, Direction::Forward);

    expect((string)$grid)->toBeString()->toContain(...Str::ucsplit($word));
    echo (string)$grid;
});
