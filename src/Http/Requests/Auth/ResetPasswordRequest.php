<?php

namespace Surya\ApiStarter\Http\Requests\Auth;

use Surya\ApiStarter\Http\Requests\ApiFormRequest;

class ResetPasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
