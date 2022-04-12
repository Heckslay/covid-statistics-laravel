<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\SyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync-countries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handles sync of countries data from covid API.
                              Is intended to run successfully only when countries table is empty.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $syncRes = SyncService::syncCountries();
        if (!$syncRes) {
            Log::alert('Failed to sync countries.');
        }

        return $syncRes;
    }
}
