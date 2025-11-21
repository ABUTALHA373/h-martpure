<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class AuthenticateAdmin extends Authenticate
{
    /**
     * Specify the guard and redirect route for admin
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // Always use 'admin' guard for this middleware
        $guards = ['admin'];

        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
    }
}
