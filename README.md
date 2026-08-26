# Laravel API Starter Kit (`surya/laravel-api-starter`)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/surya/laravel-api-starter.svg?style=flat-square)](https://packagist.org/packages/surya/laravel-api-starter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/surya/laravel-api-starter/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/surya/laravel-api-starter/actions)
[![License](https://img.shields.io/packagist/l/surya/laravel-api-starter.svg?style=flat-square)](LICENSE)

A production-ready, reusable Laravel package designed to provide a clean, Laravel-native foundation for building secure and scalable REST APIs.

---

## Features

- Laravel 12 & PHP 8.3+ Ready
- Laravel Sanctum Authentication (Register, Login, Logout, Me, Password Reset, Email Verification, Token Abilities)
- Standardized API Response Envelopes (Success, Error, Validation, Paginated)
- Automatic Request ID Middleware (`X-Request-Id` attaching & propagation)
- Dynamic Model Query Trait (`ApiQueryTrait` for Whitelisted Filter, Search, Sort & Paginate)
- Configurable Rate Limiting & API Versioning
- Safe Request Logging (With automated parameter sanitization for sensitive data)
- Built-in System Health Checks (`GET /api/v1/health` for Database & Cache)
- Artisan Code Generators (`make:api-controller`, `make:api-resource`, `make:api-request`, `make:api-service`, `make:api-crud`)

---

## Installation

Install the package via Composer:

```bash
composer require surya/laravel-api-starter
```

Run the installation wizard:

```bash
php artisan api-starter:install
```

Optionally publish example CRUD scaffolding:

```bash
php artisan api-starter:install --with-example
```

---

## Configuration

Publish the config file `config/api-starter.php`:

```php
return [
    'enabled' => env('API_STARTER_ENABLED', true),
    'prefix' => 'api',
    'versioning' => [
        'enabled' => true,
        'default' => 'v1',
    ],
    'authentication' => [
        'enabled' => true,
        'driver' => 'sanctum',
    ],
    'pagination' => [
        'default' => 15,
        'max' => 100,
    ],
    'rate_limit' => [
        'enabled' => true,
        'requests' => 60,
        'minutes' => 1,
    ],
    'logging' => [
        'requests' => false,
    ],
    'health' => [
        'enabled' => true,
        'database' => true,
        'cache' => true,
    ],
];
```

---

## Usage

### 1. Standard API Responses

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

Response format:

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

### 2. Whitelisted API Filtering, Searching, and Sorting

Use `ApiQueryTrait` on your Eloquent Model:

```php
use Illuminate\Database\Eloquent\Model;
use Surya\ApiStarter\Traits\ApiQueryTrait;

class Product extends Model
{
    use ApiQueryTrait;

    protected array $apiFilters = ['category_id', 'status'];
    protected array $apiSearch = ['name', 'description'];
    protected array $apiSorts = ['name', 'price', 'created_at'];
}
```

Query endpoints:

```http
GET /api/v1/products?filter[status]=active&search=phone&sort=-created_at&page=1&per_page=20
```

Controller usage:

```php
public function index(Request $request)
{
    $products = Product::query()
        ->apiQuery($request)
        ->apiPaginate($request);

    return $this->paginated($products);
}
```

---

## Artisan Generators

Quickly generate standard API components:

```bash
php artisan make:api-controller ProductController
php artisan make:api-resource ProductResource
php artisan make:api-request StoreProductRequest
php artisan make:api-service ProductService
php artisan make:api-crud Product
```

---

## System Diagnostics & Commands

Check package status:

```bash
php artisan api-starter:status
```

Run CLI health check:

```bash
php artisan api-starter:health
```

---

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
