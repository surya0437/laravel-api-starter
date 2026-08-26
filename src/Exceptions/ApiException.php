<?php

namespace Surya\ApiStarter\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Surya\ApiStarter\Traits\HasApiResponse;
use Throwable;

class ApiException extends Exception
{
    use HasApiResponse;

    protected int $statusCode = 400;

    protected mixed $errors = null;

    protected array $meta = [];

    public function __construct(
        string $message = '',
        int $statusCode = 400,
        mixed $errors = null,
        array $meta = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message ?: __('api-starter::api.server_error'), $statusCode, $previous);
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        $this->meta = $meta;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): mixed
    {
        return $this->errors;
    }

    public function render(Request $request): JsonResponse
    {
        return $this->error(
            message: $this->getMessage(),
            code: $this->statusCode,
            errors: $this->errors,
            meta: $this->meta
        );
    }
}
