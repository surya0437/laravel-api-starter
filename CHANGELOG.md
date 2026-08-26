# Changelog

All notable changes to `surya/laravel-api-starter` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-08-26

### Added
- Core architecture setup for Laravel 12 & PHP 8.3+.
- `ApiStarterServiceProvider` with automatic discovery, config merging, route loading, and publishing tags.
- Standardized `ApiResponse` envelope trait and `ApiController` base class.
- Standardized `ApiFormRequest` with JSON validation error exceptions.
- Custom exception hierarchy (`ApiException`, `ResourceNotFoundException`, `UnauthorizedException`, `ForbiddenException`, `BusinessLogicException`).
- Laravel Sanctum authentication workflow (Register, Login, Logout, Me, Password Reset, Email Verification).
- Sanctum token ability checking middleware (`AbilitiesMiddleware`).
- Whitelisted Eloquent query builder trait (`ApiQueryTrait`) supporting filtering, searching, sorting, and max-capped pagination.
- `RequestIdMiddleware` for ULID/UUID request tracking.
- `ApiVersionMiddleware` and `ApiRateLimitMiddleware`.
- `RequestLoggingMiddleware` with sensitive data redaction.
- Health check system (`GET /api/v1/health` and CLI `api-starter:health`).
- Artisan commands: `api-starter:install`, `api-starter:status`, `api-starter:health`, and generators (`make:api-controller`, `make:api-resource`, `make:api-request`, `make:api-service`, `make:api-crud`).
- Orchestra Testbench unit and feature test suite.
- GitHub Actions CI matrix workflow for PHP 8.3 & 8.4 against Laravel 12.
