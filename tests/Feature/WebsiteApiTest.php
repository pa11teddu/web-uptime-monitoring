<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_website()
    {
        $client = Client::factory()->create();

        $response = $this->postJson('/api/websites', [
            'client_id' => $client->id,
            'url' => 'https://newsite.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('websites', [
            'client_id' => $client->id,
            'url' => 'https://newsite.com',
        ]);
    }

    public function test_can_fetch_website_stats()
    {
        $website = Website::factory()->create();
        MonitoringLog::factory()->count(5)->create(['website_id' => $website->id]);

        $response = $this->getJson("/api/websites/{$website->id}/stats");

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_can_delete_website()
    {
        $website = Website::factory()->create();

        $response = $this->deleteJson("/api/websites/{$website->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('websites', ['id' => $website->id]);
    }
}
