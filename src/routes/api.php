<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use BgreatFit\CurrencyExchange\Http\Controllers\CurrencyExchangeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function (): void {
    Route::controller(AuthController::class)->group(function (): void {
        Route::post('user/login', 'login');
        Route::post('user/create', 'register');
    });

    Route::middleware('jwt.auth')->group(function (): void {
        Route::controller(UserController::class)->group(function (): void {
            Route::get('user', 'show');
        });
    });
    Route::controller(CurrencyExchangeController::class)->group(function (): void {
        Route::get('exchangerate', 'getExchangeRate');
    });

});
