<?php

namespace App\Http\Controllers;

use App\Game\Difficulty;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Home extends Controller
{
    public function __invoke(Request $request): \Inertia\Response
    {
        return Inertia::render('Home', props: [
            'difficulties' => array_map(fn (Difficulty $difficulty) => (array)$difficulty, Difficulty::cases()),
        ]);
    }
}
