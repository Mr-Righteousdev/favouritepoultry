<?php

namespace App\Http\Livewire;

use App\Models\Flock;
use App\Models\Feed;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\EggProduction;
use App\Models\MortalityRecord;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $totalBirds = 0;
    public $totalFlocks = 0;
    public $totalEggs = 0;
    public $feedStock = 0;
    public $todayMortality = 0;
    public $recentSales = [];
    public $lowStockFeeds = [];
    public $monthlyRevenue = [];
    public $monthlyExpenses = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // Count birds and flocks
        $this->totalFlocks = Flock::where('is_active', true)->count();
        $this->totalBirds = Flock::where('is_active', true)->sum('current_quantity');

        // Get egg production for current month
        $this->totalEggs = EggProduction::whereMonth('collection_date', Carbon::now()->month)
            ->whereYear('collection_date', Carbon::now()->year)
            ->sum('total_eggs');

        // Calculate total feed stock
        $this->feedStock = Feed::sum('quantity_kg');

        // Get today's mortality
        $this->todayMortality = MortalityRecord::whereDate('date', Carbon::today())->sum('quantity');

        // Get recent sales (last 5)
        $this->recentSales = Sale::with('flock')
            ->orderBy('sale_date', 'desc')
            ->take(5)
            ->get();

        // Get feeds that are below reorder level
        $this->lowStockFeeds = Feed::whereRaw('quantity_kg < reorder_level')
            ->orderBy('quantity_kg')
            ->get();

        // Calculate monthly revenue for the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');

            $revenue = Sale::whereMonth('sale_date', $month->month)
                ->whereYear('sale_date', $month->year)
                ->sum('total_amount');

            $expense = Expense::whereMonth('date', $month->month)
                ->whereYear('date', $month->year)
                ->sum('amount');

            $this->monthlyRevenue[$monthName] = $revenue;
            $this->monthlyExpenses[$monthName] = $expense;
        }
    }

    public function refresh()
    {
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
