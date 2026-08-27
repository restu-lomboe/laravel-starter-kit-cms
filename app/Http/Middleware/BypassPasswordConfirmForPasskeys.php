<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BypassPasswordConfirmForPasskeys
{
    /**
     * Handle an incoming request.
     *
     * For passkey management routes we bypass the password confirmation
     * requirement because the Livewire security panel already lives behind
     * auth and the WebAuthn ceremony itself is user-verifying.
     */
    public function handle(Request $request, Closure $next, ?string $redirectToRoute = null): Response
    {
        if ($request->routeIs('passkey.registration-options') ||
            $request->routeIs('passkey.store') ||
            $request->routeIs('passkey.destroy')) {
            return $next($request);
        }

        return app(RequirePassword::class)->handle($request, $next, $redirectToRoute);
    }
}
