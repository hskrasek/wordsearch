<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next, ?string ...$guards): \Symfony\Component\HttpFoundation\Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Only redirect back home if the user is registered and not on the homepage.
            if (Auth::guard($guard)->check() && $request->user()?->is_anonymous === false && $request->routeIs('login',
                'verify-token', 'register', )) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        // Only login and create an anonymous user if a user is not already logged in
        if (! Auth::check()) {
            Auth::login(
                $user = User::create(
                    ['user_agent' => $request->userAgent()]
                )->givePermissionTo('view profile', 'edit profile', 'play games')
            );

            event(new Registered($user));
        }

        return $next($request);
    }
}
