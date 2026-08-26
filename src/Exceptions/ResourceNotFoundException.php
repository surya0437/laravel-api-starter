<?php

namespace Surya\ApiStarter\Exceptions;

class ResourceNotFoundException extends ApiException
{
    public function __construct(string $message = '', mixed $errors = null, array $meta = [])
    {
        parent::__construct(
            message: $message ?: __('api-starter::api.not_found'),
            statusCode: 404,
            errors: $errors,
            meta: $meta
        );
    }
}
