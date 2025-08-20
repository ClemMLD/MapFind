<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_user_favorites()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create();
        $favorite = Favorite::create(['user_id' => $user->id, 'listing_id' => $listing->id]);
        $this->actingAs($user);

        $response = $this->get(route('favorites.index'));
        $response->assertStatus(200);
        $response->assertViewIs('account.favorites');
        $response->assertViewHas('favorites', function ($favorites) use ($favorite) {
            return $favorites->contains('id', $favorite->id);
        });
    }

    public function test_store_adds_favorite()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('favorites.store'), ['listing_id' => $listing->id]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id,
        ]);
    }

    public function test_store_prevents_duplicate_favorite()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create();
        Favorite::create(['user_id' => $user->id, 'listing_id' => $listing->id]);
        $this->actingAs($user);

        $response = $this->postJson(route('favorites.store'), ['listing_id' => $listing->id]);
        $response->assertStatus(409);
    }

    public function test_destroy_removes_favorite()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create();
        Favorite::create(['user_id' => $user->id, 'listing_id' => $listing->id]);
        $this->actingAs($user);

        $response = $this->deleteJson(route('favorites.destroy', $listing->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'listing_id' => $listing->id,
        ]);
    }
}
