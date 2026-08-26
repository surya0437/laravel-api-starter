<?php

namespace Surya\ApiStarter\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Surya\ApiStarter\Traits\HasApiResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimitMiddleware
{
    use HasApiResponse;

    public function __construct(protected RateLimiter $limiter) {}

    public function handle(Request $request, Closure $next, ?int $maxAttempts = null, ?int $decayMinutes = null): Response
    {
        if (! config('api-starter.rate_limit.enabled', true)) {
            return $next($request);
        }

        $maxAttempts = $maxAttempts ?? (int) config('api-starter.rate_limit.requests', 60);
        $decayMinutes = $decayMinutes ?? (int) config('api-starter.rate_limit.minutes', 1);

        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return $this->error(
                message: __('api-starter::api.rate_limited'),
                code: 429,
                meta: [
                    'retry_after' => $this->limiter->availableIn($key),
                ]
            );
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', (string) $this->limiter->remaining($key, $maxAttempts));

        return $response;
    }

    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return sha1($user->getAuthIdentifier());
        }

        return sha1($request->ip());
    }
}
