<?php

namespace App\Jobs;

use App\Mail\WebsiteDown;
use App\Models\MonitoringLog;
use App\Models\Website;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class CheckWebsiteStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Website $targetWebsite;

    /**
     * Initialize a new website status check job
     */
    public function __construct(Website $website)
    {
        $this->targetWebsite = $website;
    }

    /**
     * Process the website status check
     */
    public function handle(): void
    {
        $checkResult = $this->performStatusCheck();
        $this->recordMonitoringData($checkResult);
        
        if ($checkResult['isOperational'] === false) {
            $this->notifyClientOfDowntime();
        }
    }

    /**
     * Execute HTTP request to check website status
     */
    private function performStatusCheck(): array
    {
        $requestStartTime = microtime(true);
        $httpStatusCode = null;
        $isOperational = true;
        $responseDuration = 0;

        try {
            $httpResponse = Http::timeout(10)->get($this->targetWebsite->url);
            $responseDuration = microtime(true) - $requestStartTime;
            $httpStatusCode = $httpResponse->status();

            if ($httpResponse->failed()) {
                $isOperational = false;
            }
        } catch (Exception $exception) {
            $isOperational = false;
            $httpStatusCode = 0; // Indicates connection failure
            $responseDuration = 0;
            Log::warning("Failed to check website status: {$this->targetWebsite->url}", [
                'error' => $exception->getMessage()
            ]);
        }

        return [
            'isOperational' => $isOperational,
            'statusCode' => $httpStatusCode,
            'responseTime' => $responseDuration,
        ];
    }

    /**
     * Save monitoring data to database
     */
    private function recordMonitoringData(array $checkResult): void
    {
        MonitoringLog::create([
            'website_id' => $this->targetWebsite->id,
            'status_code' => $checkResult['statusCode'],
            'response_time' => $checkResult['responseTime'],
        ]);
    }

    /**
     * Send email notification when website is down
     */
    private function notifyClientOfDowntime(): void
    {
        $this->targetWebsite->load('owner');
        $clientEmail = $this->targetWebsite->owner->email;
        
        Log::info("Website unavailable: {$this->targetWebsite->url}. Notifying client: {$clientEmail}");
        
        Mail::to($clientEmail)->send(new WebsiteDown($this->targetWebsite));
    }
}
