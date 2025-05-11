<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Feed;
use App\Models\Flock;
use App\Models\Health;
use Livewire\Component;
use App\Models\FeedUsage;
use App\Models\HealthRecord;
use App\Models\EggProduction;

class Dashboard extends Component
{
    public $totalFlocks;
    public $activeFlocks;
    public $totalBirds;
    public $eggProductionToday;
    public $eggProductionWeek;
    public $averageLayingRate;
    public $feedStock;
    public $feedUsageToday;
    public $feedUsageWeek;
    public $activeTreatments;
    public $mortalityRate;

    public $chartData = [];
    public $selectedDateRange = 'week';

    protected $listeners = ['dateRangeUpdated' => 'updateDateRange'];

    public function mount()
    {
        $this->loadDashboardStats();
        $this->prepareChartData();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function updateDateRange($range)
    {
        $this->selectedDateRange = $range;
        $this->prepareChartData();
    }

    private function loadDashboardStats()
    {
        // Flock statistics
        $this->totalFlocks = Flock::count();
        $this->activeFlocks = Flock::where('is_active', 1)->count();
        $this->totalBirds = Flock::where('is_active', 1)->sum('current_quantity');

        // Egg production statistics
        $today = Carbon::today();
        $weekStart = Carbon::now()->subDays(6);

        $this->eggProductionToday = EggProduction::whereDate('created_at', $today)->sum('total_eggs');
        $this->eggProductionWeek = EggProduction::whereBetween('created_at', [$weekStart, $today])->sum('total_eggs');

        // Calculate average laying rate (eggs per bird)
        $totalActiveBirds = $this->totalBirds ?: 1; // Avoid division by zero
        $this->averageLayingRate = round($this->eggProductionToday / $totalActiveBirds * 100, 2);

        // Feed statistics
        $this->feedStock = Feed::sum('quantity');
        $this->feedUsageToday = FeedUsage::whereDate('created_at', $today)->sum('amount_used');
        $this->feedUsageWeek = FeedUsage::whereBetween('created_at', [$weekStart, $today])->sum('amount_used');

        // Health statistics
        $this->activeTreatments = Health::where('status', 'active')->count();

        // Calculate mortality rate over the past month
        $monthStart = Carbon::now()->subMonth();
        $initialCount = Flock::where('created_at', '<', $monthStart)->sum('initial_quantity');
        $currentCount = Flock::where('created_at', '<', $monthStart)->sum('current_quantity');
        $this->mortalityRate = $initialCount > 0 ? round(($initialCount - $currentCount) / $initialCount * 100, 2) : 0;
    }

    private function prepareChartData()
    {
        // Determine date range based on selection
        $endDate = Carbon::today();
        $startDate = match ($this->selectedDateRange) {
            'week' => Carbon::now()->subDays(6),
            'month' => Carbon::now()->subDays(29),
            'quarter' => Carbon::now()->subMonths(3),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(6)
        };

        // Format date format depending on range
        $groupByFormat = match ($this->selectedDateRange) {
            'week' => 'Y-m-d',
            'month' => 'Y-m-d',
            'quarter' => 'Y-m-d',
            'year' => 'Y-m',
            default => 'Y-m-d'
        };

        // Get egg production data
        $eggData = EggProduction::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("strftime('{$groupByFormat}', 'created_at') as date, SUM(total_eggs) as total")
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Get feed usage data
        $feedData = FeedUsage::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("strftime('{$groupByFormat}', 'created_at') as date, SUM(amount_used) as total")
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Create a complete date range
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            $endDate->addDay() // Add a day to include the end date
        );

        $chartData = [];
        foreach ($period as $date) {
            $formattedDate = $date->format($groupByFormat);
            $chartData[] = [
                'date' => $formattedDate,
                'eggs' => $eggData[$formattedDate] ?? 0,
                'feed' => $feedData[$formattedDate] ?? 0,
            ];
        }

        $this->chartData = $chartData;
    }
}
