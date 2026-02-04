<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteStatusBadgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_latest_log_status()
    {
        $client = Client::factory()->create();
        $website = Website::factory()->create(['client_id' => $client->id]);

        // Create an old log (fail) and new log (success)
        MonitoringLog::factory()->create([
            'website_id' => $website->id,
            'status_code' => 500,
            'created_at' => now()->subHour()
        ]);

        MonitoringLog::factory()->create([
            'website_id' => $website->id,
            'status_code' => 200,
            'created_at' => now()
        ]);

        $response = $this->getJson("/api/clients/{$client->id}/websites");

        $response->assertStatus(200)
            ->assertJsonPath('0.latest_log.status_code', 200);
    }
}
