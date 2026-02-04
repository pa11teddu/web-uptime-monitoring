<?php

namespace Tests\Unit;

use App\Jobs\CheckWebsiteStatus;
use App\Mail\WebsiteDown;
use App\Models\Client;
use App\Models\Website;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckWebsiteStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_detects_down_website_and_sends_email()
    {
        Mail::fake();

        $client = Client::factory()->create(['email' => 'test@example.com']);
        $website = Website::factory()->create(['client_id' => $client->id, 'url' => 'https://broken.com']);

        // Mock HTTP to fail
        Http::fake([
            'https://broken.com' => Http::response(null, 500),
        ]);

        $job = new CheckWebsiteStatus($website);
        $job->handle();

        Mail::assertQueued(WebsiteDown::class, function ($mail) use ($client) {
            return $mail->hasTo($client->email);
        });

        $this->assertDatabaseHas('monitoring_logs', [
            'website_id' => $website->id,
            'status_code' => 500,
        ]);
    }

    public function test_job_records_success_logs()
    {
        Mail::fake();

        $client = Client::factory()->create();
        $website = Website::factory()->create(['client_id' => $client->id, 'url' => 'https://works.com']);

        // Mock HTTP to succeed
        Http::fake([
            'https://works.com' => Http::response('OK', 200),
        ]);

        $job = new CheckWebsiteStatus($website);
        $job->handle();

        Mail::assertNothingSent();

        $this->assertDatabaseHas('monitoring_logs', [
            'website_id' => $website->id,
            'status_code' => 200,
        ]);
    }
}
