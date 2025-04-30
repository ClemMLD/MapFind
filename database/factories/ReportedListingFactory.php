<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Listing;
use App\Models\ReportedListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportedListing>
 */
class ReportedListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'listing_id' => Listing::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'reason' => $this->faker->sentence(),
        ];
    }
}
