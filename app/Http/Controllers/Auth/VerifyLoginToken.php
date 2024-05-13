<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class VerifyLoginToken extends Controller
{
    public function __invoke(Request $request, string $plainTextToken): Redirector|Application|RedirectResponse
    {
        $token = LoginToken::whereToken(hash('sha512', $plainTextToken))->firstOrFail();

        abort_unless($request->hasValidSignature() && $token->isValid(), 401);

        $token->consume();
        $token->user->markEmailAsVerified();

        auth()->login($token->user, true);

        $request->session()->regenerate(true);

        return redirect()->intended('/');
    }
}
