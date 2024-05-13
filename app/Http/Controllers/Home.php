<?php

namespace App\Http\Controllers;

use App\Game\Difficulty;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Home extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Home', props: [
            'difficulties' => array_map(fn (Difficulty $difficulty) => (array) $difficulty + ['directions' => $difficulty->directions()], Difficulty::cases()),
        ]);
    }
}
