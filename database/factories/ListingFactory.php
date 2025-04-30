<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'condition' => $this->faker->randomElement(['new', 'excellent', 'very good', 'good', 'acceptable', 'poor', 'for_parts']),
            'category_id' => Category::inRandomOrder()->first()->id,
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'address' => $this->faker->address(),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
