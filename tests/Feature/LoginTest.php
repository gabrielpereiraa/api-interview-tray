<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected string $loginUri;
    protected string $meUri;
    protected string $logoutUri;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loginUri = '/api/login';
        $this->meUri = '/api/me';
        $this->logoutUri = '/api/logout';
    }

    public function test_can_login_and_receive_token()
    {
        $userData = $this->getDefaultUserData();
        $this->createUser($userData);

        $response = $this->post($this->loginUri, [
            'email' => $userData['email'],
            'password' => $userData['password'],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'user' => ['id', 'name', 'email'],
            'token' => ['access_token', 'token_type', 'expires_in']
        ]);
    }

    public function test_login_with_invalid_credentials_fails()
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->postJson($this->loginUri, [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_can_get_authenticated_user()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->getJson($this->meUri, [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'user' => [
                'id' => $user->id,
                'email' => $user->email
            ]
        ]);
    }

    public function test_can_logout()
    {
        $user = User::factory()->create();
        $token = auth()->login($user);

        $response = $this->postJson($this->logoutUri, [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Successfully logged out']);
    }
}
