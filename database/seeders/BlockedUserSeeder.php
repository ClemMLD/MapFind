<?php

namespace Database\Seeders;

use App\Models\BlockedUser;
use Illuminate\Database\Seeder;

class BlockedUserSeeder extends Seeder
{
    public function run(): void
    {
        BlockedUser::factory()->count(50)->create();
    }
}
