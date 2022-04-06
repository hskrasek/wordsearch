<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Game extends Controller
{
    public function __invoke(Request $request, \App\Models\Game $game): Response
    {
        return Inertia::render('Game', props: (new GameResource($game))->toArray($request));
    }
}
