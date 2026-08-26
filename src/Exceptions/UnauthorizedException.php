<?php

namespace Surya\ApiStarter\Exceptions;

class UnauthorizedException extends ApiException
{
    public function __construct(string $message = '', mixed $errors = null, array $meta = [])
    {
        parent::__construct(
            message: $message ?: __('api-starter::api.unauthenticated'),
            statusCode: 401,
            errors: $errors,
            meta: $meta
        );
    }
}
