<?php

namespace Tests\Helpers;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait SaleHelper
{
    public function getSalesUri(): string
    {
        return "/sales";
    }

    public function getSellerSalesUri(Seller $seller): string
    {
        return "/sellers/{$seller->id}/sales";
    }

    public function getDefaultSaleData(): array
    {
        return [
            'amount' => fake()->randomFloat(2, 10, 1000),
            'made_at' => fake()->date()
        ];
    }

    protected function createSale(User $user, Seller $seller, array $attributes = []): Sale
    {
        return Sale::factory()->create(array_merge([
            'user_id' => $user->id,
            'seller_id' => $seller->id,
        ], $attributes));
    }

    protected function createSales(User $user, Seller $seller, int $count = 0): Collection
    {
        return Sale::factory($count)->create([
            'user_id' => $user->id,
            'seller_id' => $seller->id,
        ]);
    }
}