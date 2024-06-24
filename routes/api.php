<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\UserAttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::middleware(['guest'])->group(function () {
            Route::post('/login', [AuthController::class, 'login']);

            Route::post('forgot/password', [ForgotPasswordController::class, 'forgot']);
            Route::post('forgot/password/reset', [ForgotPasswordController::class, 'reset']);
            Route::get('forgot/password/reset/link', [ForgotPasswordController::class, 'tokenResetLink'])->name('password.reset');

            Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');
        });

        Route::middleware(['auth:api'])->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);

            Route::get('/email/verify/resend', [VerifyEmailController::class, 'resend'])
                ->middleware(['throttle:6,1'])
                ->name('verification.send');
        });
    });

    Route::prefix('user')->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::apiResource('attendances', UserAttendanceController::class);
            Route::post('attendances/time-in', [UserAttendanceController::class, 'timeIn']);
            Route::post('attendances/time-out', [UserAttendanceController::class, 'timeOut']);
        });
    });
});
