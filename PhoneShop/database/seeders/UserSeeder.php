<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// database/seeders/UserSeeder.php
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@phoneshop.test'],
            ['name' => 'Admin', 'password' => bcrypt('admin123'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'user@phoneshop.test'],
            ['name' => 'User', 'password' => bcrypt('user123'), 'role' => 'user']
        );

        User::factory()->count(10)->create(); // role mặc định 'user'
    }
}
