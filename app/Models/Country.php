<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

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
     */
    public function todayStatistic(): HasOne
    {
        return $this->hasOne(Statistic::class)->whereDate('synced_at', Carbon::today());
    }
}
