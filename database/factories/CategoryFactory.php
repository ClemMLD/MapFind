<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $category = $this->faker->randomElement($this->getCategories());

        return [
            'name' => [
                'en' => $category['en'],
                'fr' => $category['fr'],
            ],
            'color' => $this->faker->hexColor,
        ];
    }

    private function getCategories(): array
    {
        return [
            ['en' => 'Restaurants', 'fr' => 'Restaurants'],
            ['en' => 'Video games', 'fr' => 'Jeux vidéo'],
            ['en' => 'Music', 'fr' => 'Musique'],
            ['en' => 'Cinema', 'fr' => 'Cinéma'],
            ['en' => 'Sport', 'fr' => 'Sport'],
            ['en' => 'Computer science', 'fr' => 'Informatique'],
            ['en' => 'Fashion', 'fr' => 'Mode'],
            ['en' => 'Travel', 'fr' => 'Voyage'],
            ['en' => 'Cooking', 'fr' => 'Cuisine'],
            ['en' => 'Animals', 'fr' => 'Animaux'],
        ];
    }
}