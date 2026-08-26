# Laravel API Starter Kit

[![Latest Version on Packagist](https://img.shields.io/packagist/v/surya-narayan/laravel-api-starter.svg?style=flat-square)](https://packagist.org/packages/surya-narayan/laravel-api-starter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/surya0437/laravel-api-starter/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/surya0437/laravel-api-starter/actions)
[![License](https://img.shields.io/packagist/l/surya-narayan/laravel-api-starter.svg?style=flat-square)](LICENSE)

A production-ready, reusable Laravel package that provides a clean, Laravel-native foundation for building secure, scalable, and maintainable REST APIs.

Laravel API Starter Kit helps you eliminate repetitive API boilerplate by providing standardized responses, authentication, request tracing, query utilities, rate limiting, health checks, logging, and Artisan generators out of the box.

---

## Requirements

- PHP `^8.3`
- Laravel `12.x`
- Laravel Sanctum `^4.0`

---

## Features

- Laravel 12 & PHP 8.3+ Ready
- Laravel Sanctum Authentication
  - Register
  - Login
  - Logout
  - Current authenticated user
  - Password reset
  - Email verification
  - Token abilities

- Standardized API Responses
  - Success responses
  - Error responses
  - Validation errors
  - Paginated responses

- Request ID Middleware
  - Automatically generates `X-Request-Id`
  - Preserves an existing request ID
  - Supports request tracing and log correlation

- API Query Utilities
  - Whitelisted filters
  - Searching
  - Sorting
  - Pagination

- Configurable Rate Limiting
- API Versioning
- Safe Request Logging
  - Sanitizes configured sensitive parameters

- System Health Checks
  - Database connectivity
  - Cache connectivity

- Artisan Generators
  - API Controllers
  - API Resources
  - Form Requests
  - Services
  - CRUD scaffolding

- Centralized Configuration
- Laravel Package Auto-Discovery

---

## Installation

Install the package using Composer:

```bash
composer require surya-narayan/laravel-api-starter
```

Then run the installation command:

```bash
php artisan api-starter:install
```

The installer prepares the package resources and configuration required by your application.

### Install with Example CRUD

To install the package together with example CRUD scaffolding:

```bash
php artisan api-starter:install --with-example
```

---

## Configuration

Publish the package configuration:

```bash
php artisan vendor:publish --tag=api-starter-config
```

The configuration file will be available at `config/api-starter.php`.

Example configuration:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Package
    |--------------------------------------------------------------------------
    */

    'enabled' => env('API_STARTER_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | API Prefix
    |--------------------------------------------------------------------------
    */

    'prefix' => 'api',

    /*
    |--------------------------------------------------------------------------
    | API Versioning
    |--------------------------------------------------------------------------
    */

    'versioning' => [
        'enabled' => true,
        'default' => 'v1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */

    'authentication' => [
        'enabled' => true,
        'driver' => 'sanctum',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'default' => 15,
        'max' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */

    'rate_limit' => [
        'enabled' => true,
        'requests' => 60,
        'minutes' => 1,
    ],

    /*
    |--------------------------------------------------------------------------
    | Request Logging
    |--------------------------------------------------------------------------
    */

    'logging' => [
        'requests' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Health Checks
    |--------------------------------------------------------------------------
    */

    'health' => [
        'enabled' => true,
        'database' => true,
        'cache' => true,
    ],

];
```

---

## Standard API Responses

The package provides a reusable base `ApiController` with standardized response helpers.

Extend `Surya\ApiStarter\Http\Controllers\ApiController`:

```php
use Surya\ApiStarter\Http\Controllers\ApiController;

class UserController extends ApiController
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->success(
            data: $user,
            message: 'User retrieved successfully.'
        );
    }
}
```

Example response:

```json
{
  "success": true,
  "message": "User retrieved successfully.",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "meta": {
    "request_id": "01K...",
    "version": "v1"
  }
}
```

---

## API Query Trait

The `ApiQueryTrait` provides controlled filtering, searching, sorting, and pagination for Eloquent models.

### Add the Trait to a Model

```php
use Illuminate\Database\Eloquent\Model;
use Surya\ApiStarter\Traits\ApiQueryTrait;

class Product extends Model
{
    use ApiQueryTrait;

    protected array $apiFilters = [
        'category_id',
        'status',
    ];

    protected array $apiSearch = [
        'name',
        'description',
    ];

    protected array $apiSorts = [
        'name',
        'price',
        'created_at',
    ];
}
```

### Filtering

```http
GET /api/v1/products?filter[status]=active&filter[category_id]=5
```

### Searching

```http
GET /api/v1/products?search=phone
```

### Sorting

```http
GET /api/v1/products?sort=-price
```

### Pagination

```http
GET /api/v1/products?page=1&per_page=20
```

### Controller Usage

```php
use Illuminate\Http\Request;

public function index(Request $request)
{
    $products = Product::query()
        ->apiQuery($request)
        ->apiPaginate($request);

    return $this->paginated($products);
}
```

---

## Authentication

Authentication is powered by Laravel Sanctum. The package provides endpoints for:

- Registration (`POST /api/v1/auth/register`)
- Login (`POST /api/v1/auth/login`)
- Logout (`POST /api/v1/auth/logout`)
- Current authenticated user (`GET /api/v1/auth/me`)
- Password reset
- Email verification
- Token abilities check middleware

---

## API Versioning

API versioning can be enabled from the package configuration:

```php
'versioning' => [
    'enabled' => true,
    'default' => 'v1',
],
```

Endpoints structure: `/api/v1/...`

---

## Request IDs

The package provides automatic request tracing through the `X-Request-Id` header.

Example:

```http
X-Request-Id: 01K123456789
```

---

## Rate Limiting

Configured centrally in `config/api-starter.php`:

```php
'rate_limit' => [
    'enabled' => true,
    'requests' => 60,
    'minutes' => 1,
],
```

---

## Request Logging

Configured in `config/api-starter.php`:

```php
'logging' => [
    'requests' => true,
],
```

Request parameters specified in redaction filters are automatically sanitized.

---

## Health Checks

Built-in API health check endpoint:

```http
GET /api/v1/health
```

Or via CLI:

```bash
php artisan api-starter:health
```

---

## Artisan Generators

### API Controller
```bash
php artisan make:api-controller ProductController
```

### API Resource
```bash
php artisan make:api-resource ProductResource
```

### API Request
```bash
php artisan make:api-request StoreProductRequest
```

### API Service
```bash
php artisan make:api-service ProductService
```

### Complete CRUD Scaffolding
```bash
php artisan make:api-crud Product
```

---

## Available Commands

| Command                              | Description                            |
| ------------------------------------ | -------------------------------------- |
| `api-starter:install`                | Install and configure the package      |
| `api-starter:install --with-example` | Install the package with example CRUD  |
| `api-starter:status`                 | Display package status                 |
| `api-starter:health`                 | Run system health checks               |
| `make:api-controller`                | Generate an API controller             |
| `make:api-resource`                  | Generate an API resource               |
| `make:api-request`                   | Generate an API request                |
| `make:api-service`                   | Generate an API service                |
| `make:api-crud`                      | Generate a complete API CRUD structure |

---

## Testing & Quality Assurance

Install development dependencies:

```bash
composer install
```

Run test suite (Pest PHP):

```bash
composer test
```

Run code style check (Pint):

```bash
composer lint
```

Run static analysis (PHPStan):

```bash
composer analyse
```

---

## Contributing

Contributions, bug reports, and pull requests are welcome.

Before submitting a pull request, please ensure all quality checks pass:

```bash
composer test
composer lint
composer analyse
```

---

## License

The Laravel API Starter Kit is open-sourced software licensed under the [MIT License](LICENSE).

---

## Package Information

| Attribute      | Value                                                                                                 |
| -------------- | ----------------------------------------------------------------------------------------------------- |
| Package        | `surya-narayan/laravel-api-starter`                                                                   |
| PHP            | `^8.3`                                                                                                |
| Laravel        | `12.x`                                                                                                |
| Authentication | Laravel Sanctum                                                                                       |
| License        | MIT                                                                                                   |
| GitHub         | [surya0437/laravel-api-starter](https://github.com/surya0437/laravel-api-starter)                     |
| Packagist      | [surya-narayan/laravel-api-starter](https://packagist.org/packages/surya-narayan/laravel-api-starter) |

---

## Author

Surya Narayan Chaudhary
