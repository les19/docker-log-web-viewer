<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultHeaderChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request has the default header
        if (!$request->headers->has('X-Auth-Token')) {
            // If not, return a 400 Bad Request response
            return response()->json(['error' => 'Missing Token'], 400);
        }

        if ($request->headers->get('X-Auth-Token') !== config('auth.token')) {
            // If the token does not match, return a 403 Forbidden response
            return response()->json(['error' => 'Invalid Token'], 403);
        }

        return $next($request);
    }
}
