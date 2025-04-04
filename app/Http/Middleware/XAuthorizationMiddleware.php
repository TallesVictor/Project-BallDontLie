<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class XAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('X-Authorization')) {
            $token = $request->header('X-Authorization');

            if (!str_starts_with($token, 'Bearer ')) {
                $token = 'Bearer ' . $token;
            }

            $request->headers->set('Authorization', $token);
        }

        return $next($request);
    }
}
