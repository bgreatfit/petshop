<?php


namespace BgreatFit\CurrencyExchange\Tests;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;

class CurrencyExchangeTest extends TestCase
{

    public function test_successful_conversion()
    {
        // Mock the Guzzle HTTP client
        $mock = new MockHandler([
            new Response(200, [], file_get_contents( __DIR__ .'/ecb_response.xml')) // mock ECB XML response
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $client); // Override the Client instance in the service container

        $response = $this->get('/exchangerate?amount=100&currency_to_exchange=USD');

        $response
            ->assertStatus(200)
            ->assertJson([
                'data'=>[
                    'amount_in_euro' => 100,
                    'currency' => 'USD',
                    'converted_amount' => 117
                ]
            ]);
    }

    public function test_unsupported_currency_code()
    {
        $mock = new MockHandler([
            new Response(200, [], file_get_contents(__DIR__ . '/ecb_response.xml'))
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $client);

        $response = $this->get('/exchangerate?amount=100&currency_to_exchange=XYZ');

        $response
            ->assertStatus(400) // Assuming you handle this in your controller
            ->assertJson(['error' => 'Currency not supported or not found in the ECB data.']);
    }

    public function test_missing_parameters()
    {
        $response = $this->get('/exchangerate?amount=100');
        $response->assertStatus(400);

        $response = $this->get('/exchangerate?currency_to_exchange=USD');
        $response->assertStatus(400);
    }
}
