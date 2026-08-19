<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (Auth::guest()) {
            return redirect()->guest(route('login'))->with('error', 'Please login first');
        }

        abort_unless($request->user()->can($permission), 403, 'You don\'t have the permission to access this page.');

        return $next($request);
    }
}
