<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdmSeeder::class,
            UserSeeder::class,
            SellerSeeder::class,
            SaleSeeder::class
        ]);
    }
}
