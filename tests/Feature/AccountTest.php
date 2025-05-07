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
        $user = User::create([
            'name' => 'Clément Maldonado',
            'nickname' => 'Clem',
            'email' => 'clement.maldonado@icloud.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'type' => 'premium',
            'subscribed_at' => now(),
            'max_listings' => 100,
            'boosts' => 100,
            'password' => bcrypt('30072002')
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/account');

        $response->assertOk();
    }

    public function test_account_information_can_be_updated(): void
    {
        $user = User::create([
            'name' => 'Clément Maldonado',
            'nickname' => 'Clem',
            'email' => 'clement.maldonado@icloud.com',
            'role' => 'admin',
            'type' => 'premium',
            'subscribed_at' => now(),
            'max_listings' => 100,
            'boosts' => 100,
            'password' => bcrypt('30072002')
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/account/1', [
                'name' => 'Clément Maldonado',
                'email' => 'clement.maldonado@icloud.com',
            ]);

        $response
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame('Clément Maldonado', $user->name);
        $this->assertSame('clement.maldonado@icloud.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::create([
            'name' => 'Clément Maldonado',
            'nickname' => 'Clem',
            'email' => 'clement.maldonado@icloud.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'type' => 'premium',
            'subscribed_at' => now(),
            'max_listings' => 100,
            'boosts' => 100,
            'password' => bcrypt('30072002')
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/account', [
                'name' => 'Clément Maldonado',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors();

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::create([
            'name' => 'Clément Maldonado',
            'nickname' => 'Clem',
            'email' => 'clement.maldonado@icloud.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'type' => 'premium',
            'subscribed_at' => now(),
            'max_listings' => 100,
            'boosts' => 100,
            'password' => bcrypt('30072002')
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/account/1', [
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
        $user = User::create([
            'name' => 'Clément Maldonado',
            'nickname' => 'Clem',
            'email' => 'clement.maldonado@icloud.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'type' => 'premium',
            'subscribed_at' => now(),
            'max_listings' => 100,
            'boosts' => 100,
            'password' => bcrypt('30072002')
        ]);

        $response = $this
            ->actingAs($user)
            ->from('/account')
            ->delete('/account/1', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/account');

        $this->assertNotNull($user->fresh());
    }
}
