<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Surya\ApiStarter\Http\Controllers\ApiController;
use Surya\ApiStarter\Http\Requests\Auth\LoginRequest;

class LoginController extends ApiController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        /** @var class-string<Model> $userModel */
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        /** @var mixed $user */
        $user = $userModel::query()->where('email', $request->validated('email'))->first();

        if (! $user || ! Hash::check($request->validated('password'), (string) $user->password)) {
            return $this->unauthorized(__('api-starter::api.unauthenticated'));
        }

        $deviceName = $request->input('device_name', 'auth_token');
        $token = method_exists($user, 'createToken')
            ? $user->createToken($deviceName)->plainTextToken
            : null;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], __('api-starter::api.success'));
    }
}
