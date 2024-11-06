<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        if ($username == config('app.basic_name') && $password == config('app.basic_pass')) {
            return $next($request);
        }

        abort(401, 'Enter username and password.', [header('WWW-Authenticate: Basic realm="Sample Private Page"'), header('Content-Type: text/plain; charset=utf-8')]);
    }
}
