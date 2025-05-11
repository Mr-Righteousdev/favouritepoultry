<?php

namespace App\Http\Livewire;

use App\Models\Flock;
use App\Models\EggProduction;
use App\Models\FeedUsage;
use App\Models\HealthRecord;
use Livewire\Component;
use Carbon\Carbon;

class FlockAnalytics extends Component
{
    public $selectedFlock = null;
    public $flocks;
    public $flockData = null;
    public $productionData = [];
    public $feedData = [];
    public $healthData = [];

    public $dateRange = 30;

    protected $listeners = ['flockSelected' => 'selectFlock'];

    public function mount()
    {
        $this->flocks = Flock::orderBy('name')->get(['id', 'name']);
        if ($this->flocks->isNotEmpty()) {
            $this->selectedFlock = $this->flocks->first()->id;
            $this->loadFlockData();
        }
    }

    public function render()
    {
        return view('livewire.flocks-analytics');
    }

    public function selectFlock($flockId)
    {
        $this->selectedFlock = $flockId;
        $this->loadFlockData();
    }

    public function updatedDateRange()
    {
        $this->loadFlockData();
    }

    private function loadFlockData()
    {
        if (!$this->selectedFlock) {
            return;
        }

        $this->flockData = Flock::find($this->selectedFlock);

        if (!$this->flockData) {
            return;
        }

        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($this->dateRange);

        // Get egg production data for this flock
        $this->productionData = [
            'total' => EggProduction::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('quantity'),

            'daily_avg' => EggProduction::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw('AVG(quantity) as avg_quantity')
                ->first()?->avg_quantity ?? 0,

            'laying_rate' => $this->calculateLayingRate($startDate, $endDate),

            'chart_data' => EggProduction::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get(['date', 'quantity'])
                ->map(function ($item) {
                    return [
                        'date' => Carbon::parse($item->date)->format('Y-m-d'),
                        'value' => $item->quantity
                    ];
                })
        ];

        // Get feed usage data for this flock
        $this->feedData = [
            'total' => FeedUsage::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('quantity'),

            'daily_avg' => FeedUsage::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->selectRaw('AVG(quantity) as avg_quantity')
                ->first()?->avg_quantity ?? 0,

            'feed_conversion' => $this->calculateFeedConversion($startDate, $endDate),

            'chart_data' => FeedUsage::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date')
                ->get(['date', 'quantity'])
                ->map(function ($item) {
                    return [
                        'date' => Carbon::parse($item->date)->format('Y-m-d'),
                        'value' => $item->quantity
                    ];
                })
        ];

        // Get health data for this flock
        $this->healthData = [
            'records' => HealthRecord::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get(),

            'active_treatments' => HealthRecord::where('flock_id', $this->selectedFlock)
                ->where('status', 'active')
                ->count(),

            'total_treatments' => HealthRecord::where('flock_id', $this->selectedFlock)
                ->whereBetween('date', [$startDate, $endDate])
                ->count(),

            'mortality' => $this->calculateMortality()
        ];
    }

    private function calculateLayingRate($startDate, $endDate)
    {
        if (!$this->flockData) {
            return 0;
        }

        $totalEggs = EggProduction::where('flock_id', $this->selectedFlock)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('quantity');

        $days = $endDate->diffInDays($startDate) + 1;
        $avgBirds = $this->flockData->current_count; // Simplified - ideally would calculate average birds over period

        if ($days <= 0 || $avgBirds <= 0) {
            return 0;
        }

        return round(($totalEggs / ($days * $avgBirds)) * 100, 2);
    }

    private function calculateFeedConversion($startDate, $endDate)
    {
        $totalFeed = FeedUsage::where('flock_id', $this->selectedFlock)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('quantity');

        $totalEggs = EggProduction::where('flock_id', $this->selectedFlock)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('quantity');

        if ($totalEggs <= 0) {
            return 0;
        }

        return round($totalFeed / $totalEggs, 2);
    }

    private function calculateMortality()
    {
        if (!$this->flockData) {
            return 0;
        }

        $initialCount = $this->flockData->initial_count;
        $currentCount = $this->flockData->current_count;

        if ($initialCount <= 0) {
            return 0;
        }

        return round((($initialCount - $currentCount) / $initialCount) * 100, 2);
    }

    public function exportFlockData()
    {
        // Export functionality to be implemented
        $this->emit('showNotification', 'Export functionality will be implemented soon');
    }
}
