<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected string $resourceUri;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resourceUri = $this->getUsersUri();
    }

    // CREATE
    public function test_can_create_a_user()
    {
        $userData = $this->getDefaultUserData();
        $countUsersWithAdmAndCreatedUser = 2;

        $response = $this->post($this->resourceUri, $userData, $this->authHeader);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(['user']);
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
        $this->assertDatabaseCount('users', $countUsersWithAdmAndCreatedUser);
    }

    public function test_create_user_validation_fails()
    {
        $userData = [
            'name' => '',
            'password' => ''
        ];

        $response = $this->post($this->resourceUri, $userData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_create_user_with_duplicate_email_fails()
    {
        $userData = $this->getDefaultUserData();
        $userAlreadyExists = $this->createUser($userData);

        $response = $this->post($this->resourceUri, $userData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // SHOW
    public function test_can_show_a_user()
    {
        $user = $this->createUser();

        $response = $this->get("$this->resourceUri/$user->id", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['user']);
        $response->assertJsonFragment([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_show_user_not_found()
    {
        $validUser = $this->createUser();
        $invalidUserID = 99;

        $response = $this->get("$this->resourceUri/$invalidUserID", $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // LIST
    public function test_can_list_all_users()
    {
        $countUsers = 10;
        $countUsersWithAdm = $countUsers + 1;
        $this->createUsers($countUsers);

        $response = $this->get("$this->resourceUri", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee(['users']);
        $this->assertDatabaseCount('users', $countUsersWithAdm);
    }

    public function test_can_filter_users_by_email()
    {   
        $countUsers = 10;
        $countUsersWithAdmAndCreatedUser = $countUsers + 2;

        $this->createUsers($countUsers);
        $searchUser = $this->createUser();

        $queryParam = "email=$searchUser->email";
        $response = $this->get("$this->resourceUri?$queryParam", $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['user']);
        $response->assertJsonFragment([
            'id' => $searchUser->id,
            'email' => $searchUser->email
        ]);
        $this->assertDatabaseHas('users', [
            'name' => $searchUser->name,
            'email' => $searchUser->email,
        ]);
        $this->assertDatabaseCount('users', $countUsersWithAdmAndCreatedUser);
    }

    public function test_cannot_filter_users_by_invalid_email()
    {   
        $countUsers = 10;
        $this->createUsers($countUsers);
        $invalidEmail = '123';

        $queryParam = "email=$invalidEmail";
        $response = $this->get("$this->resourceUri?$queryParam", $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_cannot_filter_users_by_non_existent_email()
    {   
        $countUsers = 10;
        $this->createUsers($countUsers);
        $invalidEmail = fake()->email();

        $queryParam = "email=$invalidEmail";
        $response = $this->get("$this->resourceUri?$queryParam", $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    // UPDATE
    public function test_can_update_a_user()
    {
        $user = $this->createUser();
        $newData = $this->getDefaultUserData();

        $response = $this->put("$this->resourceUri/$user->id", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'user' => [
                'id' => $user->id,
                'name' => $newData['name'],
            ]
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newData['name'],
        ]);
    }

    public function test_update_user_not_found()
    {
        $validUser = $this->createUser();
        $invalidUserID = 99;

        $newData = [
            'name' => fake()->name(),
            'password' => fake()->password()
        ];

        $response = $this->put("$this->resourceUri/$invalidUserID", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_user_validation_fails()
    {
        $user = $this->createUser();
        $newData = [
            'name' => '',
            'password' => ''
        ];

        $response = $this->put("$this->resourceUri/$user->id", $newData, $this->authHeader);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // DELETE
    public function test_can_delete_a_user()
    {
        $user = $this->createUser();

        $response = $this->delete("$this->resourceUri/$user->id", [], $this->authHeader);
        $response->assertStatus(200);
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_delete_user_not_found()
    {
        $validUser = $this->createUser();
        $invalidUserID = 99;

        $response = $this->delete("$this->resourceUri/$invalidUserID", [], $this->authHeader);
        $response->assertStatus(404);
    }
}