<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\TokenService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class TokenServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TokenService $tokenService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenService = new TokenService();
        $this->user = User::factory()->create();
    }

    public function test_issue_and_verify_valid_token()
    {


        $token = $this->tokenService->issueToken( $this->user, 'Access Token');


        $isValid = $this->tokenService->verifyToken($token);

        $this->assertTrue($isValid);
    }

    public function test_parse_token_contains_user_id_claim()
    {

        $token = $this->tokenService->issueToken($this->user, 'Access Token');

        $parsedToken = $this->tokenService->parseToken($token);

        $this->assertArrayHasKey('user_uuid', $parsedToken);
        $this->assertEquals($this->user->uuid, $parsedToken['user_uuid']);
    }

    public function test_expired_token_verification_returns_true()
    {
        $user = User::factory()->create();
        // Set a new expiration time (e.g., 1 hour from now)
        $newExpiration =  Carbon::now();

        // Set the new value in the configuration
         Config::set('auth.jwt.expiration', $newExpiration);

        $expiredToken = $this->tokenService->issueToken($this->user, 'Expired Token');

        $isValid = $this->tokenService->isTokenExpired($expiredToken);

        $this->assertTrue($isValid);
    }
    public function test_expired_token_verification_returns_false()
    {
        // Set a new expiration time (e.g., 1 hour from now)
        $newExpiration =  Carbon::now()->addHour(2);

        // Set the new value in the configuration
        Config::set('auth.jwt.expiration', $newExpiration);

        $expiredToken = $this->tokenService->issueToken($this->user, 'Expired Token');

        $isValid = $this->tokenService->isTokenExpired($expiredToken);

        $this->assertFalse($isValid);
    }

    public function test_invalid_token_verification_returns_false()
    {
        $invalidToken = 'invalid_token_string';

        $isValid = $this->tokenService->verifyToken($invalidToken);

        $this->assertFalse($isValid);
    }
}
