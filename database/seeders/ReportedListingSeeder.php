<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportedListing;

class ReportedListingSeeder extends Seeder
{
    public function run(): void
    {
        ReportedListing::factory(100)->create();
    }
}
