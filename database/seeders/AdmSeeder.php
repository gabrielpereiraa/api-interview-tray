<?php

namespace Database\Seeders;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdmSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'adm-tray@hotmail.com',
            'password' => '12345678',
            'role' => UserRoles::ADMINISTRATOR_ID
        ]);
    }
}
