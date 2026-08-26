<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Surya\ApiStarter\Http\Controllers\ApiController;
use Surya\ApiStarter\Http\Requests\Auth\ForgotPasswordRequest;

class ForgotPasswordController extends ApiController
{
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->success(null, __($status));
        }

        return $this->error(__($status), 400);
    }
}
