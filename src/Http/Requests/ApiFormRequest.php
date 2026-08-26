<?php

namespace Surya\ApiStarter\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Surya\ApiStarter\Traits\HasApiResponse;

abstract class ApiFormRequest extends FormRequest
{
    use HasApiResponse;

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            $this->validationError(
                errors: $validator->errors()->toArray(),
                message: __('api-starter::api.validation_failed')
            )
        );
    }
}
