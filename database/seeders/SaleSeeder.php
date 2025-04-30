<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run()
    {
        Seller::all()->each(function($seller) {
            Sale::factory()->count(5)->create([
                'user_id' => User::all()->random()->id,
                'seller_id' => $seller->id,
            ]);
        });
    }
}
