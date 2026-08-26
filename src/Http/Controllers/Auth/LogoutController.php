<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Surya\ApiStarter\Http\Controllers\ApiController;

class LogoutController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return $this->success(null, __('api-starter::api.success'));
    }
}
