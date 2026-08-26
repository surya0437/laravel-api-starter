<?php

namespace Surya\ApiStarter\Exceptions;

class BusinessLogicException extends ApiException
{
    public function __construct(string $message = '', int $statusCode = 422, mixed $errors = null, array $meta = [])
    {
        parent::__construct(
            message: $message ?: __('api-starter::api.server_error'),
            statusCode: $statusCode,
            errors: $errors,
            meta: $meta
        );
    }
}
