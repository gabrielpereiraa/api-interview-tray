<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use App\Services\CommissionService;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition()
    {
        $randomAmount = $this->faker->randomFloat(2, 10, 1000);
        $commission = (new CommissionService())->calculate($randomAmount);

        return [
            'seller_id' => Seller::factory(),
            'user_id' => User::factory(),
            'amount' => $randomAmount,
            'commission' => $commission,
            'made_at' => $this->faker->dateTime(),
        ];
    }
}
