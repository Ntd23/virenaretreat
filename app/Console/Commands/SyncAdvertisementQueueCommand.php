<?php

namespace App\Console\Commands;

use App\Models\AdvertisementRequest;
use Illuminate\Console\Command;

class SyncAdvertisementQueueCommand extends Command
{
    protected $signature = 'advertisements:sync-queue';

    protected $description = 'Expire running advertisements and promote the next waiting advertisements.';

    public function handle()
    {
        $result = AdvertisementRequest::completeExpiredRunningAds();

        $this->info(sprintf(
            'Advertisement queue synced. Completed: %d. Promoted: %d. Normalized: %d.',
            $result['completed'],
            $result['promoted'],
            $result['normalized'] ?? 0
        ));
    }
}
