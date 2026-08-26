<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Surya\ApiStarter\Http\Controllers\ApiController;

class MeController extends ApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        return $this->success(
            data: $request->user(),
            message: __('api-starter::api.retrieved')
        );
    }
}
