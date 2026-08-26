<?php

namespace Surya\ApiStarter\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggingMiddleware
{
    protected array $hiddenFields = [
        'password',
        'password_confirmation',
        'token',
        'access_token',
        'refresh_token',
        'authorization',
        'secret',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        if (config('api-starter.logging.requests', false)) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $route = $request->route();

            $context = [
                'request_id' => $request->attributes->get('request_id'),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'route' => $route ? $route->getName() : $request->path(),
                'user_id' => $request->user()?->getAuthIdentifier(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => $response->getStatusCode(),
                'duration_ms' => $duration,
                'input' => $this->sanitizeInput($request->all()),
            ];

            Log::info('API Request Logged', $context);
        }

        return $response;
    }

    protected function sanitizeInput(array $input): array
    {
        foreach ($input as $key => $value) {
            if (in_array(strtolower($key), $this->hiddenFields, true)) {
                $input[$key] = '********';
            } elseif (is_array($value)) {
                $input[$key] = $this->sanitizeInput($value);
            }
        }

        return $input;
    }
}
