<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use Illuminate\{Contracts\Foundation\Application, Http\RedirectResponse, Http\Request, Routing\Redirector};

class VerifyLoginToken extends Controller
{
    public function __invoke(Request $request, string $token): Redirector|Application|RedirectResponse
    {
        $token = LoginToken::whereToken(hash('sha512', $token))->firstOrFail();

        abort_unless($request->hasValidSignature() && $token->isValid(), 401);

        $token->consume();
        $token->user->markEmailAsVerified();

        auth()->login($token->user);

        return redirect('/');
    }
}
