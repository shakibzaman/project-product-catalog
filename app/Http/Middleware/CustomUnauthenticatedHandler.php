<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class CustomUnauthenticatedHandler
{
    public function handle($request, Closure $next)
    {
        try {
            return $next($request);
        } catch (AuthenticationException $e) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated. Please login.'], 401)
                : response('Unauthenticated.', 401);
        }
    }
}
