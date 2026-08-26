<?php

namespace Surya\ApiStarter\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Surya\ApiStarter\Exceptions\ForbiddenException;
use Symfony\Component\HttpFoundation\Response;

class AbilitiesMiddleware
{
    public function handle(Request $request, Closure $next, string ...$abilities): Response
    {
        $user = $request->user();

        if (! $user || ! method_exists($user, 'tokenCan')) {
            throw new ForbiddenException('Invalid authentication token.');
        }

        foreach ($abilities as $ability) {
            if (! $user->tokenCan($ability)) {
                throw new ForbiddenException("Missing required ability: {$ability}");
            }
        }

        return $next($request);
    }
}
