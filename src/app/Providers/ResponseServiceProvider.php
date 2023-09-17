<?php

namespace App\Providers;


use Illuminate\Http\JsonResponse;
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

    protected function descriptiveResponseMethods(): void
    {
        $instance = $this;

         Response::macro('success', function ($data, $status) {
            return Response::json(["success" => 1, 'data' => $data,
                "error" => null, "errors" => [], "extra" => []], $status);

        });


        Response::macro('badRequest', function ($message = 'Validation Failure', $errors = []) use ($instance) {
            return $instance->handleErrorResponse($message, $errors, 400);
        });

    }
    public function handleErrorResponse($message, $errors, $status)
    {

        $response = [
            "success" => 0,
            'data' => [],
            "error" => $message,
            "errors" => $errors,
            "extra" => []];

        return Response::json($response, $status);
    }
}
