<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlockedUser>
 */
class BlockedUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'blocked_user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
