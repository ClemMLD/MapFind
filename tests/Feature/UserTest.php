<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BlockedUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_redirects_to_account_if_own_profile()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->get(route('users.show', $user));
        $response->assertRedirect(route('account.index'));
    }

    public function test_show_displays_user_profile()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $other = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $this->actingAs($user);

        $response = $this->get(route('users.show', $other));
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $other);
        $response->assertDontSee('incomingBlocked');
        $response->assertDontSee('outgoingBlocked');
    }

    public function test_show_displays_incoming_blocked()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $other = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        BlockedUser::create(['user_id' => $other->id, 'blocked_user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get(route('users.show', $other));
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('incomingBlocked', true);
    }

    public function test_show_displays_outgoing_blocked()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $other = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        BlockedUser::create(['user_id' => $user->id, 'blocked_user_id' => $other->id]);
        $this->actingAs($user);

        $response = $this->get(route('users.show', $other));
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('outgoingBlocked', true);
    }
}
