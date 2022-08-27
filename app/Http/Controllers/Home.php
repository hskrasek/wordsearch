<?php

namespace App\Http\Controllers;

use App\Game\Difficulty;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class Home extends Controller
{
    public function __invoke(Request $request): \Inertia\Response
    {
        if (!auth()->check()) {
            auth()->login(
                $user = User::create(
                    ['user_agent' => $request->userAgent(),]
                )->givePermissionTo('view profile', 'edit profile', 'play games')
            );

            event(new Registered($user));
        }

        return Inertia::render('Home', props: [
            'difficulties' => array_map(fn(Difficulty $difficulty) => (array)$difficulty + ['directions' => $difficulty->directions()], Difficulty::cases()),
        ]);
    }
}
