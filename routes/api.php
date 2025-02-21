<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\LeaveActionController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\UserAttendanceController;
use App\Http\Controllers\Api\UserLeaveController;
use App\Http\Controllers\Api\UserController;
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
    Route::middleware(['guest'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/login', [AuthController::class, 'login']);

            Route::post('forgot/password', [ForgotPasswordController::class, 'forgot']);
            Route::post('forgot/password/reset', [ForgotPasswordController::class, 'reset']);
            Route::get('forgot/password/reset/link', [ForgotPasswordController::class, 'tokenResetLink'])->name('password.reset');

            Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');
        });
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/me', [AuthController::class, 'me']);

            Route::get('/email/verify/resend', [VerifyEmailController::class, 'resend'])
                ->middleware(['throttle:6,1'])
                ->name('verification.send');
        });

        Route::prefix('user')->group(function () {
            Route::apiResource('attendances', UserAttendanceController::class)->only(['index']);
            Route::post('attendances/time-in', [UserAttendanceController::class, 'timeIn']);
            Route::patch('attendances/time-out', [UserAttendanceController::class, 'timeOut']);

            Route::apiResource('leaves', UserLeaveController::class)->parameters([
                'leaves' => 'leave',
            ]);
            Route::get('informations', [UserController::class, 'index']);
            Route::post('store', [UserController::class, 'store']);
            Route::patch('/update/{userId}', [UserController::class, 'update']);
            Route::delete('delete/{userId}', [UserController::class, 'destroy']);

        });

        Route::prefix('leave-action')->group(function () {
            Route::patch('approve/{leave}', [LeaveActionController::class, 'approve']);
            Route::patch('decline/{leave}', [LeaveActionController::class, 'decline']);
        });

        Route::apiResource('holidays', HolidayController::class)->only(['index']);
        Route::apiResource('departments', DepartmentController::class)->only(['index']);
        Route::apiResource('positions', PositionController::class)->only(['index']);
    });
});
