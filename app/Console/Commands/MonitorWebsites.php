<?php

namespace App\Console\Commands;

use App\Jobs\CheckWebsiteStatus;
use App\Models\Website;
use Illuminate\Console\Command;

class MonitorWebsites extends Command
{
    /**
     * Command signature
     *
     * @var string
     */
    protected $signature = 'monitor:websites';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Check the status of all active websites';

    /**
     * Process monitoring for all active websites
     */
    public function handle(): int
    {
        $this->info('Initializing website status monitoring...');

        $processedCount = 0;
        $batchSize = 100;

        Website::where('is_active', true)
            ->chunk($batchSize, function ($activeWebsites) use (&$processedCount) {
                foreach ($activeWebsites as $site) {
                    CheckWebsiteStatus::dispatch($site);
                    $processedCount++;
                }
            });

        $this->info("Successfully queued {$processedCount} monitoring job(s).");

        return Command::SUCCESS;
    }
}
