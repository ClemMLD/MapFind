<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Listing;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_view()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('listings.index'));
        $response->assertStatus(200);
        $response->assertViewIs('listings.index');
    }

    public function test_show_returns_listing()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create();

        $this->actingAs($user);
        $response = $this->get(route('listings.show', $listing));
        $response->assertStatus(200);
        $response->assertViewIs('listings.show');
    }

    public function test_store_creates_listing()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Test',
            'description' => 'Description',
            'category_id' => Category::first()->id,
            'price' => 100,
            'currency' => 'EUR',
            'address' => '123 Main St',
            'latitude' => 48.85,
            'longitude' => 2.35,
        ];

        $response = $this->postJson(route('listings.store'), $data);
        $response->assertStatus(201);
        $this->assertDatabaseHas('listings', ['title' => 'Test']);
    }

    public function test_update_modifies_listing()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $data = [
            'id' => $listing->id,
            'title' => 'New title',
        ];

        $response = $this->putJson(route('listings.update', $listing), $data);
        $response->assertStatus(202);
        $this->assertDatabaseHas('listings', ['title' => 'New title']);
    }

    public function test_destroy_deletes_listing()
    {
        $this->seed('CategorySeeder');
        $user = User::factory()->create();
        $listing = Listing::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->deleteJson(route('listings.destroy', $listing));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('listings', ['id' => $listing->id]);
    }
}
