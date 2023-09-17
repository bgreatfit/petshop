<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected TokenService $tokenService;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenService = new TokenService();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_fetch_record()
    {


        // Authenticate the user (create a token)
        $token = $this->tokenService->issueToken($this->user, 'API TOKEN');

        // Make a GET request to the protected route with the token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->get('/api/v1/user/');

        // Assert that the response status is 200 (OK)
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['id'=>$this->user->id]);

        $response->assertJson([
            'success' => 1,
            'data' => [
                'uuid' => $this->user->uuid,
                'email'=> $this->user->email
                // Add other expected fields here
            ],
        ]);
    }

    public function test_unauthenticated_user_cannot_fetch_record()
    {

        $response = $this->get('/api/v1/user');

        $response->assertStatus(401);
    }
}
