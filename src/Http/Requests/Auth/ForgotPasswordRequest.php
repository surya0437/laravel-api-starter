<?php

namespace Surya\ApiStarter\Http\Requests\Auth;

use Surya\ApiStarter\Http\Requests\ApiFormRequest;

class ForgotPasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
