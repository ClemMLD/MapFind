<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MessageSeeder::class,
            CategorySeeder::class,
            ListingSeeder::class,
            FavoriteSeeder::class,
            BlockedUserSeeder::class,
            ReportedListingSeeder::class,
        ]);
    }
}
