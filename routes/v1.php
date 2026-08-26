<?php

use Illuminate\Support\Facades\Route;
use Surya\ApiStarter\Http\Controllers\Auth\ForgotPasswordController;
use Surya\ApiStarter\Http\Controllers\Auth\LoginController;
use Surya\ApiStarter\Http\Controllers\Auth\LogoutController;
use Surya\ApiStarter\Http\Controllers\Auth\MeController;
use Surya\ApiStarter\Http\Controllers\Auth\RegisterController;
use Surya\ApiStarter\Http\Controllers\Auth\ResetPasswordController;
use Surya\ApiStarter\Http\Controllers\Auth\VerifyEmailController;
use Surya\ApiStarter\Http\Controllers\HealthController;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Health Check
    if (config('api-starter.health.enabled', true)) {
        Route::get('/health', [HealthController::class, 'index'])->name('api.v1.health');
    }

    // Authentication Routes
    if (config('api-starter.authentication.enabled', true)) {
        Route::prefix('auth')->group(function () {
            // Guest routes
            Route::post('/register', RegisterController::class)->name('api.v1.auth.register');
            Route::post('/login', LoginController::class)->name('api.v1.auth.login');
            Route::post('/forgot-password', ForgotPasswordController::class)->name('api.v1.auth.forgot-password');
            Route::post('/reset-password', ResetPasswordController::class)->name('api.v1.auth.reset-password');

            // Verification
            Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('api.v1.auth.verify-email');
            Route::post('/email/resend', [VerifyEmailController::class, 'resend'])->name('api.v1.auth.resend-email');

            // Authenticated routes
            Route::middleware('auth:sanctum')->group(function () {
                Route::get('/me', MeController::class)->name('api.v1.auth.me');
                Route::post('/logout', LogoutController::class)->name('api.v1.auth.logout');
            });
        });
    }
});
