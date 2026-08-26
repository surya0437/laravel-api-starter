<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Surya\ApiStarter\Http\Controllers\ApiController;

class VerifyEmailController extends ApiController
{
    public function verify(Request $request, string|int $id, string $hash): JsonResponse
    {
        /** @var class-string<Model> $userModel */
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        /** @var mixed $user */
        $user = $userModel::query()->findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return $this->error(__('api-starter::api.validation_failed'), 400);
        }

        if ($user->hasVerifiedEmail()) {
            return $this->success(null, 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->success(null, 'Email verified successfully.');
    }

    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return $this->unauthorized();
        }

        if ($user->hasVerifiedEmail()) {
            return $this->success(null, 'Email already verified.');
        }

        $user->sendEmailVerificationNotification();

        return $this->success(null, 'Verification link sent.');
    }
}
