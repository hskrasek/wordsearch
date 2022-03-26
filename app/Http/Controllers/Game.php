<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Game extends Controller
{
    public function __invoke(Request $request, \App\Models\Game $game): Response
    {
        return Inertia::render('Game', props: [
            'difficulty' => $game->difficulty->name,
            'grid'       => $game->grid->toArray()['grid'],
            'words'      => $game->words,
            'uuid'       => $game->uuid,
        ]);
    }
}
