<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellerFactory extends Factory
{
    public function definition()
    {
        return [
            'created_by' => User::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email()
        ];
    }
}
