<?php

namespace App\Console\Commands;

use App\Services\SyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handles sync of COVID-19 Statistics from Covid API.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $syncRes = SyncService::syncStatistics();
        if (!$syncRes) {
            Log::alert('Failed to sync statistical data.');
        }

        return $syncRes;
    }
}
