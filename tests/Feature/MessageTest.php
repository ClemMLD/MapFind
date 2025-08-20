<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_shows_conversations()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $other = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        Message::factory()->create([
            'sender_id' => $user->id,
            'receiver_id' => $other->id,
            'content' => 'Salut !',
        ]);

        $this->actingAs($user);
        $response = $this->get(route('messages.index'));
        $response->assertStatus(200);
        $response->assertViewIs('messages.index');
        $response->assertSee('Salut');
    }

    public function test_show_displays_conversation_with_user()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $other = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        Message::factory()->create([
            'sender_id' => $user->id,
            'receiver_id' => $other->id,
            'content' => 'Bonjour !',
        ]);

        $this->actingAs($user);
        $response = $this->get(route('messages.show', $other));
        $response->assertStatus(200);
        $response->assertViewIs('messages.show');
        $response->assertSee('Bonjour');
    }

    public function test_store_sends_message()
    {
        $user = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);
        $other = User::factory()->create(['is_active' => true, 'email_verified_at' => now()]);

        $this->actingAs($user);
        $data = [
            'receiver_id' => $other->id,
            'content' => 'Test message',
        ];
        $response = $this->postJson(route('messages.store'), $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'sender_id' => $user->id,
            'receiver_id' => $other->id,
            'content' => 'Test message',
        ]);
    }
}
