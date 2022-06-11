<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendLoginLink extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private readonly string $plainTextToken, private readonly Carbon $expiresAt)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this
            ->subject(config('app.name') . ' Login Verification')
            ->markdown('login-link')
            ->with(
                [
                    'url' => URL::temporarySignedRoute('verify-login', $this->expiresAt, [
                        'token' => $this->plainTextToken,
                    ]),
                ]
            );
    }
}
