<?php

namespace App\Services;

use App\Jobs\StatisticSyncJob;
use App\Models\Country;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SyncService
{

    /**
     * @return bool
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Handles fetch and local saving of countries from Covid API.
     * Does the save only in case when local countries table is empty.
     */
    public static function syncCountries(): bool
    {
        if (Country::count()) {
            return false;
        }
        $response = Http::get(config('covid_api.base_url') . config('covid_api.countries_path'));
        if ($response->status() !== 200) {
            return false;
        }
        $countries = $response->json();
        DB::beginTransaction();
        try {
            foreach ($countries as $country) {
                $newCountry = new Country();
                $newCountry->code = $country['code'];
                $newCountry->name = json_encode($country['name']);
                if (!$newCountry->save()) {
                    DB::rollBack();
                    return false;
                }
            }
        } catch (JsonEncodingException|\Exception $ex) {
            DB::rollBack();
            // @todo: Add sentry call here.
            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * @return bool
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public static function syncStatistics(): bool
    {
        $countries = Country::with('todayStatistic')->get();
        foreach ($countries as $country) {
            try {
                dispatch(new StatisticSyncJob($country));
            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }
}
