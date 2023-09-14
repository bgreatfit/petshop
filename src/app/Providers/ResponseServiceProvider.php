<?php

namespace App\Providers;


use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->descriptiveResponseMethods();
    }

    protected function descriptiveResponseMethods() {

        Response::macro('created', function ($data) {
            return Response::json(["success" => 1, 'data' => $data,
                "error" => null, "errors" => [], "extra" => []], 201);

        });
    }
}
