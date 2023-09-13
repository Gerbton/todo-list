<?php

namespace Tests\Feature\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected const API_TOKEN_URL = '/api/v1/token';

    public function testLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'email' => fake()->safeEmail(),
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(self::API_TOKEN_URL, [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                'id',
                'email',
            ],
            'token',
        ]);
        $response->assertJson([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
    }

    public function testLoginWithInvalidCredentials()
    {
        $user = User::factory()->create([
            'email' => fake()->safeEmail(),
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson(self::API_TOKEN_URL, [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['error']);
    }

    public function testLoginWithNonExistingUser()
    {
        $response = $this->postJson(self::API_TOKEN_URL, [
            'email' => 'nonexisting@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['error']);
    }
}
