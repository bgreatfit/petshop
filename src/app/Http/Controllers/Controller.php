<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="Laravel Swagger API documentation example",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="mioshine2011@gmail.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * @OA\Server(
 *     description="Laravel Swagger API server",
 *     url="http://localhost:8080"
 * )
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     in="header",
 *     name="X-APP-ID",
 *     securityScheme="X-APP-ID"
 * )
 *
 * @OA\Get(
 *      path="/api/v1/exchangerate",
 *      operationId="getExchangeRate",
 *      tags={"Currency"},
 *      summary="Get exchange rate",
 *      description="Returns exchange rate based on daily rates from ECB",
 *      @OA\Parameter(
 *          name="amount",
 *          description="Amount in Euro",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="number"
 *          )
 *      ),
 *      @OA\Parameter(
 *          name="currency_to_exchange",
 *          description="Target currency to convert to",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(response=200, description="OK"),
 *      @OA\Response(response=401, description="Unauthorized"),
 *      @OA\Response(response=404, description="Page not found"),
 *      @OA\Response(response=422, description="Unprocessable Entity"),
 *      @OA\Response(response=500, description="Internal server error"),
 *     )
 */
class Controller extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;
}
