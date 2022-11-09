<?php

namespace App\Http\Controllers;

use App\Game\Stats;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GameStats extends Controller
{
    public function __invoke(Request $request, Game $game): Response|JsonResponse
    {
        return \response()->json(
            (new Stats())->calculate($game)
        );
    }
}
