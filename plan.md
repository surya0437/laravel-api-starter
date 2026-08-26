# Laravel API Starter Kit v1.0 — Final Implementation Plan

Build a production-ready, reusable Laravel API Starter Kit package:

`surya/laravel-api-starter`

The package should provide a clean, Laravel-native foundation for developing secure and scalable REST APIs while remaining lightweight, extensible, configurable, and easy to install.

## 1. Core Architecture

Use the following architecture:

```text
HTTP Request
    ↓
Middleware
    ├── Request ID
    ├── Rate Limiting
    ├── API Version
    └── Request Logging
    ↓
Form Request
    ↓
Controller
    ↓
Service
    ↓
Eloquent / Domain Logic
    ↓
API Resource
    ↓
Response Formatter
    ↓
JSON Response
```

Do not force a repository layer. Use repositories only when they provide genuine value.

Controllers must remain thin, services must contain reusable business logic, and Eloquent should remain the primary persistence layer.

---

# 2. Package Foundation

Target:

* PHP 8.3+
* Laravel 12+
* Laravel Sanctum
* Orchestra Testbench
* PHPUnit/Pest
* Laravel Pint
* PHPStan/Larastan

Use:

```text
Surya\ApiStarter\
```

for the package namespace.

Package:

```text
surya/laravel-api-starter
```

Use PSR-4 autoloading and proper Laravel package discovery.

---

# 3. Package Structure

Use a structure similar to:

```text
laravel-api-starter/
├── config/
│   └── api-starter.php
├── database/
│   └── migrations/
├── resources/
│   └── lang/
│       └── en/
│           └── api.php
├── routes/
│   ├── api.php
│   └── v1.php
├── src/
│   ├── Commands/
│   ├── Contracts/
│   ├── Events/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Notifications/
│   ├── Providers/
│   ├── Services/
│   ├── Support/
│   └── Traits/
├── tests/
│   ├── Feature/
│   ├── Unit/
│   └── TestCase.php
├── composer.json
├── phpunit.xml
├── pint.json
├── phpstan.neon
├── README.md
├── CHANGELOG.md
└── LICENSE
```

---

# 4. Service Provider

Create:

```php
Surya\ApiStarter\Providers\ApiStarterServiceProvider
```

The provider must register:

* Configuration
* Routes
* Migrations
* Commands
* Translation files
* Service bindings
* Package events/listeners where appropriate

Provide publish tags:

```text
api-starter-config
api-starter-migrations
api-starter-routes
api-starter-translations
api-starter
```

---

# 5. Configuration

Create:

```text
config/api-starter.php
```

Configuration should cover:

```php
return [

    'enabled' => true,

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
        'activity' => false,
    ],

    'health' => [
        'enabled' => true,
        'database' => true,
        'cache' => true,
    ],

    'response' => [
        'include_message' => true,
        'include_meta' => true,
        'include_request_id' => true,
    ],

];
```

Everything important must be configurable.

Avoid excessive `.env` configuration.

---

# 6. Standard API Response

Create a centralized response system.

Successful response:

```json
{
    "success": true,
    "message": "User retrieved successfully.",
    "data": {},
    "meta": {
        "request_id": "01K...",
        "version": "v1"
    }
}
```

Paginated response:

```json
{
    "success": true,
    "message": "Users retrieved successfully.",
    "data": [],
    "meta": {
        "request_id": "01K...",
        "version": "v1",
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 100,
            "last_page": 7
        }
    }
}
```

Error response:

```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "email": [
            "The email field is required."
        ]
    },
    "meta": {
        "request_id": "01K..."
    }
}
```

Provide helpers for:

```text
success
created
updated
deleted
error
validationError
notFound
unauthorized
forbidden
paginated
```

---

# 7. API Controller

Create:

```php
ApiController
```

with reusable response methods.

Example:

```php
return $this->success(
    data: $user,
    message: 'User retrieved successfully.'
);
```

Do not put business logic inside the base controller.

---

# 8. API Form Request

Create:

```php
ApiFormRequest
```

extending Laravel's `FormRequest`.

All package API requests should extend this class.

It should provide consistent validation-error behavior.

Example:

```php
class LoginRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }
}
```

---

# 9. API Resources

Use Laravel's native `JsonResource`.

Do not replace Laravel's resource system unnecessarily.

Example:

```php
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
```

Use the response layer for the common API envelope.

Allow Laravel to handle:

* Resource collections
* Pagination
* Links
* Additional metadata

---

# 10. Authentication

Use Laravel Sanctum.

Provide:

```text
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/me
```

Do **not** implement a fake OAuth-style refresh-token system on top of standard Sanctum personal access tokens.

Use Sanctum's normal token lifecycle:

```text
Login
 ↓
Create token
 ↓
Use token
 ↓
Revoke token
```

If refresh tokens are required in the future, treat that as a separate authentication driver/feature.

---

# 11. Sanctum Token Abilities

Support Sanctum abilities/scopes.

Example:

```php
$user->createToken(
    'mobile',
    [
        'users:read',
        'users:write',
    ]
);
```

Support middleware such as:

```text
abilities:users:read
abilities:users:write
```

Allow applications to define their own abilities.

---

# 12. Existing User Model Compatibility

Never force applications to replace:

```text
App\Models\User
```

Provide package traits/helpers where useful.

The host application's User model should remain the source of truth.

The package should integrate with Laravel's existing authentication configuration.

---

# 13. Password Reset

Provide:

```text
POST /api/v1/auth/forgot-password
POST /api/v1/auth/reset-password
```

Use Laravel's native Password Broker.

Do not reinvent password-reset token storage.

---

# 14. Email Verification

Provide:

```text
GET  /api/v1/auth/email/verify/{id}/{hash}
POST /api/v1/auth/email/resend
```

Use Laravel's native email verification system.

---

# 15. Query Builder

Provide optional API query functionality.

Support:

```text
filter
search
sort
pagination
```

Example:

```text
GET /api/v1/users?filter[status]=active
GET /api/v1/users?search=john
GET /api/v1/users?sort=name
GET /api/v1/users?sort=-created_at
GET /api/v1/users?page=2&per_page=25
```

Only explicitly allowed fields may be queried.

Never allow arbitrary client-controlled SQL columns.

---

# 16. Model Query Configuration

Allow models to define:

```php
protected array $apiFilters = [
    'status',
    'role',
];

protected array $apiSearch = [
    'name',
    'email',
];

protected array $apiSorts = [
    'name',
    'created_at',
];
```

Then provide a simple API such as:

```php
User::query()
    ->apiQuery(request())
    ->paginate();
```

Keep this functionality optional and modular.

---

# 17. Pagination

Provide configurable pagination:

```text
?page=1
?per_page=15
```

Respect:

```php
'pagination.max'
```

Never allow unlimited results.

Return standardized pagination metadata.

---

# 18. Middleware

Provide:

```text
RequestIdMiddleware
ApiVersionMiddleware
ApiRateLimitMiddleware
RequestLoggingMiddleware
```

Avoid using response middleware to blindly wrap every Laravel response.

Response formatting should primarily happen through explicit API responses/resources.

Request ID should be:

* Generated if missing
* Added to the request
* Added to the response
* Added to logs
* Included in API metadata

---

# 19. Rate Limiting

Use Laravel's native rate limiter.

Support configurable limits.

Return:

```http
429 Too Many Requests
```

with:

```json
{
    "success": false,
    "message": "Too many requests. Please try again later."
}
```

Authentication endpoints should have stricter limits where appropriate.

---

# 20. Request Logging

Request logging must be:

**disabled by default.**

When enabled, support logging:

* Request ID
* HTTP method
* Route
* URL
* User ID
* IP
* User agent
* Status
* Duration
* Timestamp

Never log:

```text
password
password_confirmation
token
access_token
refresh_token
authorization
secret
```

Allow the logging implementation to be replaced.

---

# 21. Activity Logging

Activity logging must also be optional.

Expose:

```php
ActivityLoggerContract
```

so applications can provide their own implementation.

Avoid making a third-party activity logging package mandatory.

---

# 22. Health Check

Provide:

```text
GET /api/v1/health
```

Example:

```json
{
    "success": true,
    "message": "API is healthy.",
    "data": {
        "status": "ok",
        "database": "ok",
        "cache": "ok"
    }
}
```

Allow individual checks to be disabled.

---

# 23. Exceptions

Create:

```text
ApiException
ResourceNotFoundException
UnauthorizedException
ForbiddenException
BusinessLogicException
```

Handle Laravel exceptions consistently.

Support:

```text
400
401
403
404
409
422
429
500
503
```

Never expose stack traces or sensitive internals in production.

---

# 24. Events

Provide useful events:

```text
ApiRequestStarted
ApiRequestCompleted
ApiRequestFailed
UserAuthenticated
UserLoggedOut
```

Events should allow host applications to extend behavior without modifying package code.

---

# 25. API Versioning

Support:

```text
/api/v1
/api/v2
```

Versioning must be configurable.

Add:

```http
X-API-Version: v1
```

to responses where enabled.

Allow applications to disable versioning entirely.

---

# 26. CORS

Do not implement a custom CORS engine.

Use Laravel's existing CORS infrastructure.

Only provide package-level configuration/documentation if necessary.

---

# 27. User CRUD

Do **not** make User CRUD a mandatory package feature.

Instead, provide it as an optional reference/example implementation.

For example:

```bash
php artisan api-starter:install --with-example
```

could publish:

```text
UserController
UserResource
UserRequest
User routes
```

The core package should not take ownership of the host application's user management.

---

# 28. Artisan Commands

Provide:

```bash
php artisan api-starter:install
php artisan api-starter:status
php artisan api-starter:health
```

Generators:

```bash
php artisan make:api-controller ProductController
php artisan make:api-resource Product
php artisan make:api-request StoreProductRequest
php artisan make:api-service ProductService
php artisan make:api-crud Product
```

Never overwrite existing files unless:

```bash
--force
```

is explicitly provided.

---

# 29. Installation

Support:

```bash
composer require surya/laravel-api-starter
```

Then:

```bash
php artisan api-starter:install
```

The installation command should:

1. Publish configuration
2. Publish migrations
3. Configure routes if requested
4. Ask about optional logging
5. Ask about API versioning
6. Ask about example resources
7. Optionally run migrations
8. Display next steps

The command must be safe to execute multiple times.

---

# 30. Database Logging

Only create package-specific tables when required.

Potential tables:

```text
api_request_logs
api_activity_logs
```

Do not duplicate:

* users
* password reset tables
* Sanctum tables

when Laravel/Sanctum already provides them.

---

# 31. Localization

Provide:

```text
resources/lang/en/api.php
```

Allow applications to publish and override translations.

Include messages for:

```text
success
validation_failed
unauthenticated
unauthorized
forbidden
not_found
server_error
rate_limited
health_check
```

---

# 32. Testing

Use Orchestra Testbench.

Create tests for:

### Core

* Service provider
* Configuration
* Routes
* Installation command

### Authentication

* Registration
* Login
* Invalid credentials
* Logout
* Current user
* Token abilities
* Password reset
* Email verification

### Responses

* Success
* Created
* Updated
* Deleted
* Validation
* Unauthorized
* Forbidden
* Not found
* Server error
* Pagination

### Query

* Filtering
* Searching
* Sorting
* Invalid fields
* Pagination limits

### Middleware

* Request ID
* Rate limiting
* API version
* Request logging

### Health

* Database
* Cache
* Failure scenarios

### Generators

* Controller
* Resource
* Request
* Service
* CRUD

---

# 33. Static Analysis and Formatting

Provide:

```bash
composer test
composer lint
composer analyse
```

Equivalent commands:

```bash
vendor/bin/phpunit
vendor/bin/pint --test
vendor/bin/phpstan analyse
```

All must pass before release.

---

# 34. CI/CD

Create GitHub Actions.

Test at minimum:

```text
PHP 8.3 + Laravel 12
PHP 8.4 + Laravel 12
```

Run:

```text
composer install
tests
Pint
PHPStan
```

Prevent releases when the CI pipeline fails.

---

# 35. Documentation

README must cover:

* Installation
* Configuration
* Authentication
* Sanctum abilities
* Responses
* Validation
* Pagination
* Filtering
* Searching
* Sorting
* API versioning
* Rate limiting
* Logging
* Health check
* Artisan generators
* Extending the package
* Testing
* Troubleshooting

Include real request/response examples.

---

# 36. Extensibility

Expose contracts for extension points:

```text
ApiResponseContract
RequestLoggerContract
ActivityLoggerContract
TokenManagerContract
```

Allow host applications to override package implementations through Laravel's service container.

Do not require developers to modify package source code.

---

# 37. Package Quality

The package must:

* Follow PSR-12
* Follow Laravel conventions
* Use dependency injection
* Avoid unnecessary abstractions
* Avoid global state
* Avoid hardcoded configuration
* Avoid unnecessary dependencies
* Avoid duplicated Laravel functionality
* Protect sensitive information
* Be database-driver independent
* Be testable
* Be documented
* Be Packagist-ready

---

# 38. Release Strategy

Use semantic versioning:

```text
1.0.0
1.1.0
1.1.1
2.0.0
```

Document breaking changes in:

```text
CHANGELOG.md
```

The first release should focus on a stable, well-tested core rather than trying to support every possible API feature.

---

# 39. Implementation Phases

Implement in this order.

## Phase 1 — Core Foundation

```text
Package setup
Service provider
Configuration
Routes
API response system
Exceptions
ApiController
ApiFormRequest
API Resources
Request ID
```

## Phase 2 — Authentication

```text
Sanctum
Registration
Login
Logout
Current user
Token abilities
Password reset
Email verification
```

## Phase 3 — Query Utilities

```text
Pagination
Filtering
Searching
Sorting
ApiQueryTrait
```

## Phase 4 — Infrastructure

```text
Rate limiting
Request logging
Events
Health checks
```

## Phase 5 — Developer Experience

```text
Installation command
Status command
Health command
API controller generator
API resource generator
API request generator
API service generator
CRUD generator
Optional example resources
```

## Phase 6 — Quality & Release

```text
PHPUnit/Testbench
Pint
PHPStan
GitHub Actions
Documentation
CHANGELOG
Packagist readiness
```

---

# 40. Final Acceptance Criteria

The package is considered v1.0 complete only when:

* Composer installation works
* Laravel package discovery works
* Installation command works
* Configuration publishing works
* Routes work
* Sanctum authentication works
* Token abilities work
* Password reset works
* Email verification works
* API responses are standardized
* Exceptions are standardized
* Request IDs work
* Rate limiting works
* Filtering works
* Searching works
* Sorting works
* Pagination works
* Health check works
* Optional logging works
* Events work
* Artisan generators work
* Tests pass
* Pint passes
* PHPStan passes
* CI passes
* Documentation is complete
* No application-specific assumptions are hardcoded

The final package should feel like a **natural extension of Laravel**, not a separate framework layered on top of Laravel.

The priority for v1.0 is:

```text
Simplicity
    ↓
Laravel-native architecture
    ↓
Security
    ↓
Developer experience
    ↓
Extensibility
    ↓
Testing
    ↓
Documentation
```

Avoid adding features simply because they are technically possible. Every feature must provide clear value to an API developer while keeping the package maintainable.
