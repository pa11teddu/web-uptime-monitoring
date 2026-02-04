<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_clients()
    {
        Client::factory()->count(3)->create();

        $response = $this->getJson('/api/clients');


        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_can_fetch_client_websites()
    {
        $client = Client::factory()->create();
        Website::factory()->count(2)->create(['client_id' => $client->id]);

        $response = $this->getJson("/api/clients/{$client->id}/websites");

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_can_lookup_client_by_email()
    {
        $client = Client::factory()->create(['email' => 'findme@example.com']);

        $response = $this->postJson('/api/clients/lookup', [
            'email' => 'findme@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJson(['id' => $client->id, 'email' => 'findme@example.com']);

        $responseMissing = $this->postJson('/api/clients/lookup', [
            'email' => 'missing@example.com'
        ]);

        $responseMissing->assertStatus(404);
    }
}
