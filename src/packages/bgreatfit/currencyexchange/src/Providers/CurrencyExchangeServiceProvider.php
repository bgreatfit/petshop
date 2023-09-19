<?php

namespace BgreatFit\CurrencyExchange\Providers;

use Illuminate\Support\ServiceProvider;

class CurrencyExchangeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->publishes([
            __DIR__.'/../config/currency-exchange.php' => config_path('currency-exchange.php'),
        ]);
    }
}
