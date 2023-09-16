<?php

namespace Tests\Feature;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected const API = '/api/v1/user/create';

    public function test_valid_user_creation()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => '123 Main St',
            'phone_number' => '1234567890',
            'is_marketing' => true,
        ];


        // Send a POST request to create a user
        $response = $this->json('POST',self::API , $userData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['first_name' => 'John']);
    }

    public function test_invalid_user_creation()
    {
        $invalidUserData = [
            // Missing required fields
            'email' => 'johndoe@example.com',
            // Invalid email format
            'first_name' => 'Jo',
            // Password too short
            'password' => 'pass',
            'password_confirmation' => 'pass',
            // Password confirmation doesn't match
            'address' => '123 Main St',
            'phone_number' => '12345678901234567890', // Exceeds max length
            'is_marketing' => 'invalid_boolean', // Invalid boolean
        ];

        // Ensure the request fails validation
        $validator = Validator::make($invalidUserData, (new RegisterUserRequest())->rules());
        $this->assertFalse($validator->passes());

        // Send a POST request with invalid data to create a user
        $response = $this->json('POST', self::API, $invalidUserData);

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }
    public function testValidUserRegistration()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => '123 Main St',
            'phone_number' => '1234567890',
            'is_marketing' => true,
        ];

        $response = $this->json('POST', '/api/v1/user/create', $userData);

        $response->assertStatus(201);

        $response->assertJson([
            'success' => 1,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }


}
