<?php

namespace Surya\ApiStarter\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait ApiQueryTrait
{
    /**
     * Scope a query to apply allowed API filters, search, sorting, and pagination.
     */
    public function scopeApiQuery(Builder $query, ?Request $request = null): Builder
    {
        $request = $request ?? request();

        $this->applyFilters($query, $request);
        $this->applySearch($query, $request);
        $this->applySort($query, $request);

        return $query;
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        $allowedFilters = property_exists($this, 'apiFilters') ? (array) $this->apiFilters : [];
        $inputFilters = $request->input('filter', []);

        if (! is_array($inputFilters) || empty($allowedFilters)) {
            return;
        }

        foreach ($inputFilters as $field => $value) {
            if (in_array($field, $allowedFilters, true) && $value !== null && $value !== '') {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, '=', $value);
                }
            }
        }
    }

    protected function applySearch(Builder $query, Request $request): void
    {
        $allowedSearch = property_exists($this, 'apiSearch') ? (array) $this->apiSearch : [];
        $searchTerm = $request->input('search');

        if (! $searchTerm || empty($allowedSearch)) {
            return;
        }

        $query->where(function (Builder $q) use ($allowedSearch, $searchTerm) {
            foreach ($allowedSearch as $index => $field) {
                if ($index === 0) {
                    $q->where($field, 'LIKE', "%{$searchTerm}%");
                } else {
                    $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
                }
            }
        });
    }

    protected function applySort(Builder $query, Request $request): void
    {
        $allowedSorts = property_exists($this, 'apiSorts') ? (array) $this->apiSorts : [];
        $sortParam = $request->input('sort');

        if (! $sortParam || empty($allowedSorts)) {
            return;
        }

        $sortFields = explode(',', (string) $sortParam);

        foreach ($sortFields as $field) {
            $field = trim($field);
            $direction = 'asc';

            if (str_starts_with($field, '-')) {
                $direction = 'desc';
                $field = ltrim($field, '-');
            }

            if (in_array($field, $allowedSorts, true)) {
                $query->orderBy($field, $direction);
            }
        }
    }

    /**
     * Helper to get paginated result with configured per_page max caps.
     */
    public function scopeApiPaginate(Builder $query, ?Request $request = null, ?int $defaultPerPage = null)
    {
        $request = $request ?? request();

        $default = $defaultPerPage ?? config('api-starter.pagination.default', 15);
        $max = config('api-starter.pagination.max', 100);

        $perPage = (int) $request->input('per_page', $default);
        $perPage = max(1, min($perPage, $max));

        return $query->paginate($perPage);
    }
}
