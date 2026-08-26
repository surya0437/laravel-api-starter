<?php

namespace Surya\ApiStarter\Exceptions;

class ForbiddenException extends ApiException
{
    public function __construct(string $message = '', mixed $errors = null, array $meta = [])
    {
        parent::__construct(
            message: $message ?: __('api-starter::api.forbidden'),
            statusCode: 403,
            errors: $errors,
            meta: $meta
        );
    }
}
