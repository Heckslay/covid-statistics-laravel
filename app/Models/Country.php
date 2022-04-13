<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;


    /**
     * @return mixed
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Selects and returns countries with respective statistics
     * totals. Runs fast enough ~50ms, but probably can be improved
     * to avoid generated subquery usage.
     */
    public static function getSummaryPerCountry()
    {
        return self::withSum('statistics', 'deaths')
            ->withSum('statistics', 'confirmed')
            ->withSum('statistics', 'recovered')
            ->get();
    }

    /**
     * @return Attribute
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * A mutator for name column, to return pristine values
     * instead of redundantly encoded ones.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true));
    }

    /**
     * @return Attribute
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Generic mutator for replacing null values with numeric
     * integer 0s.
     */
    protected function replaceNull(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value === null ? 0 : (int)$value
        );
    }

    /**
     * @return Attribute
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Mutator for death sums field. Essentially replaces null
     * with 0 if no records found and nothing to sum there.
     */
    protected function statisticsSumDeaths(): Attribute
    {
        return $this->replaceNull();
    }

    /**
     * @return Attribute
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Mutator for confirmed sums field. Essentially replaces null
     * with 0 if no records found and nothing to sum there.
     */
    protected function statisticsSumConfirmed(): Attribute
    {
        return $this->replaceNull();
    }

    /**
     * @return Attribute
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Mutator for recovered sums field. Essentially replaces null
     * with 0 if no records found and nothing to sum there.
     */
    protected function statisticsSumRecovered(): Attribute
    {
        return $this->replaceNull();
    }

    /**
     * @return HasMany
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public function statistics(): HasMany
    {
        return $this->hasMany(Statistic::class);
    }

    /**
     * @return HasOne
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     * Relational method for fetching today's statistics for a country.
     */
    public function todayStatistic(): HasOne
    {
        return $this->hasOne(Statistic::class)->whereDate('synced_at', Carbon::today());
    }
}
