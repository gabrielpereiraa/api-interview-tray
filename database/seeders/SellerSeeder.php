<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function($user) {
            Seller::factory()->count(5)->create(['created_by' => $user->id]);
        });
    }
}
