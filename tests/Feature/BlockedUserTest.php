<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlockedUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_blocked_users()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $blocked = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        BlockedUser::create(['user_id' => $user->id, 'blocked_user_id' => $blocked->id]);
        $this->actingAs($user);

        $response = $this->get(route('blocked-users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('blocked-users.index');
        $response->assertViewHas('users', function ($users) use ($blocked) {
            return $users->contains('id', $blocked->id);
        });
    }

    public function test_store_blocks_user()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $blocked = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->postJson(route('blocked-users.store'), ['user_id' => $blocked->id]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('blocked_users', [
            'user_id' => $user->id,
            'blocked_user_id' => $blocked->id,
        ]);
    }

    public function test_store_cannot_block_self()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->postJson(route('blocked-users.store'), ['user_id' => $user->id]);
        $response->assertStatus(422);
        $this->assertDatabaseMissing('blocked_users', [
            'user_id' => $user->id,
            'blocked_user_id' => $user->id,
        ]);
    }

    public function test_store_cannot_block_twice()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $blocked = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        BlockedUser::create(['user_id' => $user->id, 'blocked_user_id' => $blocked->id]);
        $this->actingAs($user);

        $response = $this->postJson(route('blocked-users.store'), ['user_id' => $blocked->id]);
        $response->assertStatus(403);
    }

    public function test_destroy_unblocks_user()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $blocked = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        BlockedUser::create(['user_id' => $user->id, 'blocked_user_id' => $blocked->id]);
        $this->actingAs($user);

        $response = $this->deleteJson(route('blocked-users.destroy', $blocked));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('blocked_users', [
            'user_id' => $user->id,
            'blocked_user_id' => $blocked->id,
        ]);
    }

    public function test_destroy_cannot_unblock_self()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->deleteJson(route('blocked-users.destroy', $user));
        $response->assertStatus(422);
    }
}
