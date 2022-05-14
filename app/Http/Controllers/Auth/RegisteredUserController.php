<?php

namespace App\Http\Controllers\Auth;

use App\Actions\SendLoginLink;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, SendLoginLink $sendLoginLink)
    {
        $request->validate(
            [
                'username' => 'required|string|max:16|unique:users',
                'email'    => 'required|string|email|max:255|unique:users',
            ]
        );

        /** @var User $user */
        $user = $request->user();
        $user->update(
            [
                'username' => $request->username,
                'email'    => $request->email,
            ]
        );

        event(new Registered($user));

        $sendLoginLink($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
