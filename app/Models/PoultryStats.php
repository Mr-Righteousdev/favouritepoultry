<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PoultryStats extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_birds',
        'egg_production',
        'feed_consumption',
        'mortality_count',
        'mortality_reason',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_birds' => 'integer',
        'egg_production' => 'integer',
        'feed_consumption' => 'decimal:2',
        'mortality_count' => 'integer',
    ];

    /**
     * Get the weekly egg production statistics
     *
     * @return array
     */
    public static function getWeeklyEggStats()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(7);

        return self::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('SUM(egg_production) as total_eggs, AVG(egg_production) as avg_eggs')
            ->first();
    }

    /**
     * Get the weekly feed consumption statistics
     *
     * @return array
     */
    public static function getWeeklyFeedStats()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(7);

        return self::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('SUM(feed_consumption) as total_feed, AVG(feed_consumption) as avg_feed')
            ->first();
    }

    /**
     * Get the weekly mortality statistics
     *
     * @return array
     */
    public static function getWeeklyMortalityStats()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays(7);

        return self::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('SUM(mortality_count) as total_mortality')
            ->first();
    }

    /**
     * Calculate feed conversion ratio
     *
     * @param int $days
     * @return float
     */
    public static function getFeedConversionRatio($days = 30)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($days);

        $stats = self::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('SUM(feed_consumption) as total_feed, SUM(egg_production) as total_eggs')
            ->first();

        if ($stats && $stats->total_eggs > 0) {
            return round($stats->total_feed / $stats->total_eggs, 2);
        }

        return 0;
    }

    /**
     * Get today's statistics
     *
     * @return mixed
     */
    public static function getToday()
    {
        return self::whereDate('date', Carbon::today())->first();
    }
}
