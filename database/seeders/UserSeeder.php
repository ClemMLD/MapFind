<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Clément Maldonado',
            'nickname' => 'Clem',
            'email' => 'clement.maldonado@icloud.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'type' => 'premium',
            'subscribed_at' => now(),
            'max_listings' => 100,
            'boosts' => 100,
            'password' => bcrypt('30072002')
        ]);

        User::factory(99)->create();
    }
}
