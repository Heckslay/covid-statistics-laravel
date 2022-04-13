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
     * @return mixed
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Selects total sums of confirmed, death and recovered
     * cases of COVID pandemic. Makes sure that all values are numeric.
     */
    public static function getTotalSummary(): mixed
    {
        $summaryItems = self::first(
            DB::Raw('sum(confirmed) as total_confirmed,
                           sum(deaths) as total_deaths,
                           sum(recovered) as total_recovered'))
            ->toArray();
        foreach ($summaryItems as &$summaryItem) {
            $summaryItem = $summaryItem === null ? 0 : $summaryItem;
        }

        return $summaryItems;
    }

    /**
     * @param $country
     * @return bool
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * A function to fetch and save COVID statistics for a
     * provided country code. Makes sure to have one country
     * statistic record per day. Also handles update of existing
     * record if ran multiple times during the day.
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
     * A dynamic field setting function to avoid manual
     * setting of the model's fields. Goal - Reduce code amount.
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
