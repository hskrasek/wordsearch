<?php

use App\Exceptions\Game\GridException;
use App\Game\Direction;
use App\Game\Grid;
use Illuminate\Support\Arr;

// beforeAll(function () {
//
// });

it('can insert a word into the grid in any direction', function (string $direction) {
    $word = 'APPLE';

    $grid = new Grid(15);
    $grid->insertWord($word, Direction::from($direction));
    $grid->finalize();

    expect($grid->findWord('APPLE', $grid->getWordCoordinates()['APPLE']))->toBeTrue();
})->with('directions');

it('can insert multiple words in any direction', function () {
    $grid       = new Grid(15);
    $directions = Direction::cases();
    shuffle($directions);

    $grid->insertWord('APPLE', Arr::random($directions));

    $grid->insertWord('UNMARRED', Arr::random($directions));

    $grid->insertWord('OXYTOCIN', Arr::random($directions));

    $grid->insertWord('HALO', Arr::random($directions));

    $grid->finalize();

    expect($grid->findWord('APPLE', $grid->getWordCoordinates()['APPLE']))->toBeTrue();
    expect($grid->findWord('UNMARRED', $grid->getWordCoordinates()['UNMARRED']))->toBeTrue();
    expect($grid->findWord('OXYTOCIN', $grid->getWordCoordinates()['OXYTOCIN']))->toBeTrue();
    expect($grid->findWord('HALO', $grid->getWordCoordinates()['HALO']))->toBeTrue();
});

it('does not allow you to find words not on the grid', function () {
    $grid = new Grid(10);
    $grid->insertWord('APPLE', Direction::North);

    $grid->findWord('HALO', []);
})->throws(GridException::class, 'HALO is not within the grid');

it('throws an exception if a word cannot be inserted, despite retries', function () {
    $grid = new Grid(10);
    $grid->finalize();

    $grid->insertWord('APPLE', Direction::North);
})->throws(GridException::class);
