<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ?string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && optional($request->user())->is_anonymous === false) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        Auth::login(
            $user = User::create(
                ['user_agent' => $request->userAgent(),]
            )->givePermissionTo('view profile', 'edit profile', 'play games')
        );

        event(new Registered($user));

        return $next($request);
    }
}
