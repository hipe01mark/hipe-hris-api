<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
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
        });
    });
});
