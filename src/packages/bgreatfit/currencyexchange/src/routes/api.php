<?php

use BgreatFit\CurrencyExchange\Http\Controllers\CurrencyExchangeController;
use Illuminate\Support\Facades\Route;

Route::controller(CurrencyExchangeController::class)->group(function (): void {
    Route::get('exchangerate', 'getExchangeRate');
});
