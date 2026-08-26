<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Surya\ApiStarter\Http\Controllers\ApiController;
use Surya\ApiStarter\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController extends ApiController
{
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->success(null, __($status));
        }

        return $this->error(__($status), 400);
    }
}
