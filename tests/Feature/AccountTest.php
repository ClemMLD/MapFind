<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('account.index'));

        $response->assertOk();
    }

    public function test_account_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch(route('account.update', $user), [
                'name' => 'Clément Maldonado',
                'email' => 'nouveau@email.com',
            ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Clément Maldonado', $user->name);
        $this->assertSame('nouveau@email.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch(route('account.update', $user), [
                'name' => $user->name,
                'email' => $user->email,
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('30072002'),
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('account.destroy', $user), [
                'password' => '30072002',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('30072002'),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('account.index'))
            ->delete(route('account.destroy', $user), [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect(route('account.index'));

        $this->assertNotNull($user->fresh());
    }

    public function test_edit_account_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('account.edit', $user));

        $response->assertOk();
        $response->assertViewIs('account.edit');
    }

    public function test_user_can_delete_avatar(): void
    {
        $user = User::factory()->create(['image' => 'avatars/test.jpg']);

        $response = $this->actingAs($user)
            ->deleteJson(route('account.avatar'));

        $response->assertStatus(204);
        $this->assertNull($user->fresh()->image);
    }

    public function test_account_listings_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('account.listings'));

        $response->assertOk();
        $response->assertViewIs('account.listings');
        $response->assertViewHas('listings');
    }

    public function test_account_upgrade_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('account.upgrade'));

        $response->assertOk();
        $response->assertViewIs('account.upgrade');
    }
}
