<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Statistic extends Model
{
    use HasFactory;

    /**
     * @param $country
     * @return bool
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public static function fetchStatistic($country): bool
    {
        $todayStatistic = $country->todayStatistic;
        $statEntry = Http::post(config('covid_api.base_url') . config('covid_api.statistics_path'), [
            'code' => $country->code
        ])->json();
        $statEntry['country_id'] = $country->id;
        $statistic = $todayStatistic ?: new Statistic();
        $statistic->setFields($statEntry);
        $statistic->synced_at = Carbon::now();
        if (!$statistic->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @return void
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public function setFields($data)
    {
        $tableFields = DB::getSchemaBuilder()->getColumnListing($this->getTable());
        foreach ($tableFields as $tableField) {
            if (isset($data[$tableField])) {
                $this->{$tableField} = $data[$tableField];
            }
        }
    }
}
