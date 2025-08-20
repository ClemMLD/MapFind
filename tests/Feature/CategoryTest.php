<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_categories_in_json()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $category = Category::factory()->create([
            'name' => ['en' => 'Books', 'fr' => 'Livres'],
            'color' => '#ff0000',
        ]);

        $response = $this->getJson(route('categories.index'));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $category->id,
                'name' => 'Books',
                'color' => '#ff0000',
            ]);
    }

    public function test_index_returns_categories_in_requested_language()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $category = Category::factory()->create([
            'name' => ['en' => 'Books', 'fr' => 'Livres'],
            'color' => '#00ff00',
        ]);

        $response = $this->getJson(route('categories.index', ['language' => 'fr']));
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $category->id,
                'name' => 'Livres',
                'color' => '#00ff00',
            ]);
    }

    public function test_index_returns_404_if_language_not_found()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        Category::factory()->create([
            'name' => ['en' => 'Books'],
            'color' => '#0000ff',
        ]);

        $response = $this->getJson(route('categories.index', ['language' => 'de']));
        $response->assertStatus(404);
    }
}
