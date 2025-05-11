<?php

namespace App\Http\Livewire;

use App\Models\Flock;
use App\Models\EggProduction;
use App\Models\FeedUsage;
use App\Models\HealthRecord;
use Livewire\Component;
use Carbon\Carbon;

class PoultrySummary extends Component
{
    public $timeframe = 'week';
    public $flockData = [];
    public $eggData = [];
    public $feedData = [];
    public $healthData = [];

    public function mount()
    {
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.poultry-summary');
    }

    public function updatedTimeframe()
    {
        $this->loadData();
    }

    private function loadData()
    {
        // Determine date range based on timeframe
        $endDate = Carbon::today();
        $startDate = $this->getStartDate();

        // Load flock data
        $this->flockData = [
            'total' => Flock::count(),
            'active' => Flock::where('status', 'active')->count(),
            'birds' => Flock::where('status', 'active')->sum('current_count'),
            'recent' => Flock::orderBy('created_at', 'desc')
                ->limit(5)
                ->get(['id', 'name', 'breed', 'initial_count', 'current_count', 'created_at'])
        ];

        // Load egg production data
        $this->eggData = [
            'total' => EggProduction::whereBetween('date', [$startDate, $endDate])->sum('quantity'),
            'average' => EggProduction::whereBetween('date', [$startDate, $endDate])
                ->selectRaw('AVG(quantity) as avg_quantity')
                ->first()?->avg_quantity ?? 0,
            'today' => EggProduction::whereDate('date', $endDate)->sum('quantity'),
            'chart' => $this->getEggChartData($startDate, $endDate)
        ];

        // Load feed usage data
        $this->feedData = [
            'total' => FeedUsage::whereBetween('date', [$startDate, $endDate])->sum('quantity'),
            'average' => FeedUsage::whereBetween('date', [$startDate, $endDate])
                ->selectRaw('AVG(quantity) as avg_quantity')
                ->first()?->avg_quantity ?? 0,
            'today' => FeedUsage::whereDate('date', $endDate)->sum('quantity'),
            'chart' => $this->getFeedChartData($startDate, $endDate)
        ];

        // Load health data
        $this->healthData = [
            'active_treatments' => HealthRecord::where('status', 'active')->count(),
            'recent' => HealthRecord::orderBy('date', 'desc')
                ->limit(5)
                ->get(['id', 'flock_id', 'issue', 'treatment', 'date', 'status']),
            'mortality' => HealthRecord::whereBetween('date', [$startDate, $endDate])
                ->where('issue', 'like', '%mortality%')
                ->count()
        ];
    }

    private function getStartDate()
    {
        return match ($this->timeframe) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'quarter' => Carbon::now()->subMonths(3),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subWeek()
        };
    }

    private function getEggChartData($startDate, $endDate)
    {
        $groupFormat = $this->timeframe === 'year' ? 'Y-m' : 'Y-m-d';

        return EggProduction::whereBetween('date', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(date, '{$groupFormat}') as label, SUM(quantity) as value")
            ->groupBy('label')
            ->orderBy('label')
            ->get()
            ->toArray();
    }

    private function getFeedChartData($startDate, $endDate)
    {
        $groupFormat = $this->timeframe === 'year' ? 'Y-m' : 'Y-m-d';

        return FeedUsage::whereBetween('date', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT(date, '{$groupFormat}') as label, SUM(quantity) as value")
            ->groupBy('label')
            ->orderBy('label')
            ->get()
            ->toArray();
    }

    public function exportSummary()
    {
        // Logic to export the summary data to CSV/Excel
        $this->emit('showNotification', 'Export functionality will be implemented soon.');
    }

    public function refreshData()
    {
        $this->loadData();
        $this->emit('showNotification', 'Data refreshed successfully.');
    }
}
