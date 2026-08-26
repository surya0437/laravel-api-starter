<?php

namespace Surya\ApiStarter\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersionMiddleware
{
    public function handle(Request $request, Closure $next, ?string $version = null): Response
    {
        if (! config('api-starter.versioning.enabled', true)) {
            return $next($request);
        }

        $apiVersion = $version
            ?? $request->header('X-API-Version')
            ?? config('api-starter.versioning.default', 'v1');

        $request->attributes->set('api_version', $apiVersion);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-API-Version', $apiVersion);

        return $response;
    }
}
