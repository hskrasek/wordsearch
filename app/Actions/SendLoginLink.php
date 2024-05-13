<?php

declare(strict_types=1);

namespace App\Actions;

use App\Mail\SendLoginLink as SendLoginLinkMailable;
use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Str;

class SendLoginLink
{
    public function __construct(private Mailer $mailer)
    {
    }

    public function __invoke(User $user): void
    {
        $plainTextToken = Str::random(32);

        /** @var LoginToken $token */
        $token = $user->loginTokens()->create(
            [
                'token' => hash('sha512', $plainTextToken),
                'expires_at' => now()->addMinutes(15),
            ]
        );

        $this->mailer->to($user)->queue(new SendLoginLinkMailable($plainTextToken, $token->expires_at));
    }
}
