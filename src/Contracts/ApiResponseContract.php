<?php

namespace Surya\ApiStarter\Contracts;

use Illuminate\Http\JsonResponse;

interface ApiResponseContract
{
    public function success(mixed $data = null, ?string $message = null, int $code = 200, array $meta = []): JsonResponse;

    public function created(mixed $data = null, ?string $message = null, array $meta = []): JsonResponse;

    public function updated(mixed $data = null, ?string $message = null, array $meta = []): JsonResponse;

    public function deleted(?string $message = null, array $meta = []): JsonResponse;

    public function error(string $message, int $code = 400, mixed $errors = null, array $meta = []): JsonResponse;

    public function validationError(mixed $errors, ?string $message = null, array $meta = []): JsonResponse;

    public function notFound(?string $message = null, array $meta = []): JsonResponse;

    public function unauthorized(?string $message = null, array $meta = []): JsonResponse;

    public function forbidden(?string $message = null, array $meta = []): JsonResponse;

    public function paginated(mixed $resource, ?string $message = null, array $meta = []): JsonResponse;
}
