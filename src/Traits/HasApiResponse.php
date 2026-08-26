<?php

namespace Surya\ApiStarter\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait HasApiResponse
{
    public function success(mixed $data = null, ?string $message = null, int $code = 200, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? __('api-starter::api.success'),
            'data' => $data,
            'meta' => $this->buildMeta($meta),
        ];

        return response()->json($response, $code);
    }

    public function created(mixed $data = null, ?string $message = null, array $meta = []): JsonResponse
    {
        return $this->success(
            data: $data,
            message: $message ?? __('api-starter::api.created'),
            code: 201,
            meta: $meta
        );
    }

    public function updated(mixed $data = null, ?string $message = null, array $meta = []): JsonResponse
    {
        return $this->success(
            data: $data,
            message: $message ?? __('api-starter::api.updated'),
            code: 200,
            meta: $meta
        );
    }

    public function deleted(?string $message = null, array $meta = []): JsonResponse
    {
        return $this->success(
            data: null,
            message: $message ?? __('api-starter::api.deleted'),
            code: 200,
            meta: $meta
        );
    }

    public function error(string $message, int $code = 400, mixed $errors = null, array $meta = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'meta' => $this->buildMeta($meta),
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public function validationError(mixed $errors, ?string $message = null, array $meta = []): JsonResponse
    {
        return $this->error(
            message: $message ?? __('api-starter::api.validation_failed'),
            code: 422,
            errors: $errors,
            meta: $meta
        );
    }

    public function notFound(?string $message = null, array $meta = []): JsonResponse
    {
        return $this->error(
            message: $message ?? __('api-starter::api.not_found'),
            code: 404,
            meta: $meta
        );
    }

    public function unauthorized(?string $message = null, array $meta = []): JsonResponse
    {
        return $this->error(
            message: $message ?? __('api-starter::api.unauthenticated'),
            code: 401,
            meta: $meta
        );
    }

    public function forbidden(?string $message = null, array $meta = []): JsonResponse
    {
        return $this->error(
            message: $message ?? __('api-starter::api.forbidden'),
            code: 403,
            meta: $meta
        );
    }

    public function paginated(mixed $resource, ?string $message = null, array $meta = []): JsonResponse
    {
        $data = $resource;
        $paginationMeta = [];

        if ($resource instanceof LengthAwarePaginator) {
            $data = $resource->items();
            $paginationMeta = [
                'current_page' => $resource->currentPage(),
                'per_page' => $resource->perPage(),
                'total' => $resource->total(),
                'last_page' => $resource->lastPage(),
            ];
        } elseif ($resource instanceof ResourceCollection && $resource->resource instanceof LengthAwarePaginator) {
            $paginator = $resource->resource;
            $data = $resource->resolve();
            $paginationMeta = [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ];
        }

        $meta['pagination'] = $paginationMeta;

        return $this->success(
            data: $data,
            message: $message ?? __('api-starter::api.retrieved'),
            code: 200,
            meta: $meta
        );
    }

    protected function buildMeta(array $extraMeta = []): array
    {
        $meta = [];

        if (config('api-starter.response.include_request_id', true)) {
            $requestId = request()->attributes->get('request_id')
                ?? request()->header('X-Request-Id');

            if ($requestId) {
                $meta['request_id'] = $requestId;
            }
        }

        if (config('api-starter.versioning.enabled', true)) {
            $version = request()->attributes->get('api_version')
                ?? request()->header('X-API-Version')
                ?? config('api-starter.versioning.default', 'v1');

            $meta['version'] = $version;
        }

        return array_merge($meta, $extraMeta);
    }
}
