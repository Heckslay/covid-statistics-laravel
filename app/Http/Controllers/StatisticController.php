<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Statistic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * A route for fetching COVID statistics summary per country.
     */
    public function index(Request $request): JsonResponse
    {
        $countriesWithStatistics = Country::getSummaryPerCountry();
        return $countriesWithStatistics->count() ? $this->success($countriesWithStatistics): $this->noContent();
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * A route for fetching total summary of confirmed, death and recovered
     * cases of the COVID pandemic.
     */
    public function getSummary(Request $request): JsonResponse
    {
        $summaryStatistics = Statistic::getTotalSummary();
        return $summaryStatistics ? $this->success($summaryStatistics): $this->noContent();
    }
}
