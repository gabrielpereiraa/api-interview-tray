<?php

namespace Tests\Helpers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait SellerHelper
{
    public function getSellersUri(): string
    {
        return '/sellers';
    }

    public function getDefaultSellerData(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email()
        ];
    }

    protected function createSellers(User $createdBy, int $count = 0): Collection
    {
        return Seller::factory($count)->create(['created_by' => $createdBy->id]);
    }

    protected function createSeller(User $createdBy, array $attributes = []): Seller
    {
        if (empty($attributes)) {
            $attributes = $this->getDefaultSellerData();
        }

        return Seller::factory()->create(array_merge([
            'created_by' => $createdBy->id
        ], $attributes));
    }
}