<?php
namespace BgreatFit\CurrencyExchange\Http\Controllers;

use BgreatFit\CurrencyExchange\Http\CurrencyExchangeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class CurrencyExchangeController extends \Illuminate\Routing\Controller
{
    private Client $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getExchangeRate(CurrencyExchangeRequest $request): JsonResponse
    {
        $amount = $request->input('amount');
        $currency = $request->input('currency_to_exchange', 'Euro');

        $response = $this->client->get('https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');


        // Handle XML parsing and fetch the exchange rate
        $xml = simplexml_load_string($response->getBody());
        $rate = null;
        foreach ($xml->Cube->Cube->Cube as $currencyRate) {
            if ($currencyRate['currency'] == $currency) {
                $rate = floatval($currencyRate['rate']);
                break;
            }
        }
        // Check if the rate was found
        if ($rate === null) {
            return response()->badRequest('Currency not supported or not found in the ECB data.');
        }

        return response()->success([
            'amount_in_euro' => $amount,
            'currency' => $currency,
            'converted_amount' => $amount * $rate
        ], 200);
    }

}
