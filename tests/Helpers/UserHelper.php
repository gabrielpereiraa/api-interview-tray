<?php

namespace Tests\Helpers;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait UserHelper
{
    public function getUsersUri(): string
    {
        return '/users';
    }

    public function getDefaultUserData(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => fake()->password()
        ];
    }

    protected function createAdmUser(): User
    {
        return $this->createUser(['role' => UserRoles::ADMINISTRATOR_ID]);
    }

    protected function createUser(array $attributes = []): User
    {
        if (empty($attributes)) {
            $attributes = $this->getDefaultUserData();
        }

        return User::factory()->create($attributes);
    }

    protected function createUsers(int $count = 0): Collection
    {
        return User::factory($count)->create();
    }
}