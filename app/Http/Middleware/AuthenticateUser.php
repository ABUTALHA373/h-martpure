<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateUser extends Authenticate
{
    /**
     * Specify the guard and redirect route for user
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Always use 'user' guard for this middleware
        $guards = ['user'];

        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
