<?php

use App\Game\Direction;

dataset('directions', function (): array {
    return array_map(
        fn(Direction $direction) => $direction->value,
        Direction::cases()
    );
});
