<?php

namespace App\Http\Controllers\Auth;

use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Actions\SendLoginLink;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, SendLoginLink $sendLoginLink): RedirectResponse
    {
        $request->validate(
            [
                'username' => 'required|string|profane:en,es|max:16|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
            ]
        );

        /** @var User $user */
        $user = $request->user();
        $user->update(
            [
                'username' => $request->username,
                'email' => $request->email,
            ]
        );

        event(new Registered($user));

        $sendLoginLink($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
