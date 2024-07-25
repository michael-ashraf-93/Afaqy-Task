<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(Request): (Response) $next
     * @param int $maxAttempts
     * @param int $decayMinutes
     * @param string|null $parameterName // 'vehicle.id'
     * @return Response
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 5, int $decayMinutes = 1, ?string $parameterName = null): Response
    {
        $key = 'rate_limit_' . $request->ip() . '_' . $request->path();
        if ($parameterName) {
            $key .= "_$parameterName:{$request->route()->parameter($parameterName)}";
        }

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json(['message' => 'Too Many Requests'], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
