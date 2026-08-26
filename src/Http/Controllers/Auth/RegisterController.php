<?php

namespace Surya\ApiStarter\Http\Controllers\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Surya\ApiStarter\Http\Controllers\ApiController;
use Surya\ApiStarter\Http\Requests\Auth\RegisterRequest;

class RegisterController extends ApiController
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        /** @var class-string<Model> $userModel */
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        $user = $userModel::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $deviceName = $request->input('device_name', 'auth_token');
        $token = method_exists($user, 'createToken')
            ? $user->createToken($deviceName)->plainTextToken
            : null;

        return $this->created([
            'user' => $user,
            'token' => $token,
        ], __('api-starter::api.created'));
    }
}
