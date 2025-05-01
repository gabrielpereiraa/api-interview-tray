<?php

namespace Database\Factories;

use App\Enums\UserRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'role' =>  $this->faker->randomElement(UserRoles::all()),
            'password' => Hash::make('12345678')
        ];
    }
}
