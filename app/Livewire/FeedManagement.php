<?php

namespace App\Livewire;

use App\Models\Feed;
use App\Models\Flock;
use Livewire\Component;
use App\Livewire\FeedTable;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\DB;

class FeedManagement extends Component
{
    use WithPagination;

    public $feed_id;
    public $feed_name;
    public $feed_type;
    public $quantity;
    public $unit_price;
    public $purchase_date;
    public $expiry_date;
    public $supplier;
    public $flock_id;
    public $amount_used;
    public $usage_date;

    public $isOpen = false;
    public $isUsageOpen = false;
    public $isEditMode = false;
    public $searchTerm = '';

    public function render()
    {
        $query = Feed::query();

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('feed_name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('feed_type', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('supplier', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $feeds = $query->orderBy('purchase_date', 'desc')->paginate(10);
        $flocks = Flock::where('is_active', 1)->get();

        return view('livewire.feed-management', [
            'feeds' => $feeds,
            'flocks' => $flocks,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function openUsageModal()
    {
        $this->isUsageOpen = true;
    }

    public function closeUsageModal()
    {
        $this->isUsageOpen = false;
    }

    private function resetInputFields()
    {
        $this->feed_id = null;
        $this->feed_name = '';
        $this->feed_type = '';
        $this->quantity = '';
        $this->unit_price = '';
        $this->purchase_date = date('Y-m-d');
        $this->expiry_date = '';
        $this->supplier = '';
        $this->flock_id = '';
        $this->amount_used = '';
        $this->usage_date = date('Y-m-d');
    }

    public function store()
    {
        $this->validate([
            'feed_name' => 'required|string|max:255',
            'feed_type' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:purchase_date',
            'supplier' => 'nullable|string|max:255',
        ]);

        if ($this->isEditMode) {
            $feed = Feed::find($this->feed_id);
            $feed->update([
                'feed_name' => $this->feed_name,
                'feed_type' => $this->feed_type,
                'quantity' => $this->quantity,
                'unit_price' => $this->unit_price,
                'purchase_date' => $this->purchase_date,
                'expiry_date' => $this->expiry_date,
                'supplier' => $this->supplier,
            ]);
            // session()->flash('message', 'Feed updated successfully.');
            Toaster::success('Feed updated successfully.');
        } else {
            Feed::create([
                'feed_name' => $this->feed_name,
                'feed_type' => $this->feed_type,
                'quantity' => $this->quantity,
                'unit_price' => $this->unit_price,
                'purchase_date' => $this->purchase_date,
                'expiry_date' => $this->expiry_date,
                'supplier' => $this->supplier,
            ]);
            // session()->flash('message', 'Feed added successfully.');
            Toaster::success('Feed added successfully.');
        }

        $this->dispatch('$refresh')->to(FeedTable::class);
        $this->closeModal();
        $this->resetInputFields();
    }
    #[On('edit')]
    public function edit($id)
    {
        $feed = Feed::findOrFail($id);
        $this->feed_id = $id;
        $this->feed_name = $feed->feed_name;
        $this->feed_type = $feed->feed_type;
        $this->quantity = $feed->quantity;
        $this->unit_price = $feed->unit_price;
        $this->purchase_date = $feed->purchase_date;
        $this->expiry_date = $feed->expiry_date;
        $this->supplier = $feed->supplier;

        $this->isEditMode = true;
        $this->openModal();
    }

    #[On('recordUsage')]
    public function recordUsage($id)
    {
        $this->feed_id = $id;
        $this->resetUsageFields();
        $this->openUsageModal();
    }

    private function resetUsageFields()
    {
        $this->flock_id = '';
        $this->amount_used = '';
        $this->usage_date = date('Y-m-d');
    }

    public function storeUsage()
    {
        $this->validate([
            'feed_id' => 'required|exists:feeds,id',
            'flock_id' => 'required|exists:flocks,id',
            'amount_used' => 'required|numeric|min:0.01',
            'usage_date' => 'required|date',
        ]);

        $feed = Feed::find($this->feed_id);

        if ($feed->quantity < $this->amount_used) {
            session()->flash('error', 'Insufficient feed quantity available.');
            return;
        }

        DB::transaction(function () use ($feed) {
            // Record feed usage
            DB::table('feed_usage')->insert([
                'feed_id' => $this->feed_id,
                'flock_id' => $this->flock_id,
                'amount_used' => $this->amount_used,
                'usage_date' => $this->usage_date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update feed quantity
            $feed->quantity -= $this->amount_used;
            $feed->save();
        });
        Toaster::success('Feed usage recorded successfully.');
        $this->dispatch('$refresh')->to(FeedTable::class);
        $this->closeUsageModal();
        $this->resetUsageFields();
    }

    public function delete($id)
    {
        $feed = Feed::find($id);

        // Check if feed has any usages
        $hasUsages = DB::table('feed_usage')->where('feed_id', $id)->exists();

        if ($hasUsages) {
            session()->flash('error', 'Cannot delete feed that has been used. Consider marking it as finished instead.');
            return;
        }

        $feed->delete();
        session()->flash('message', 'Feed deleted successfully.');
    }

    public function getFeedSummary()
    {
        $totalFeed = Feed::sum('quantity');
        $lowStockFeeds = Feed::where('quantity', '<', 10)->count();
        $expiringFeeds = Feed::whereDate('expiry_date', '<=', now()->addDays(30))
            ->whereDate('expiry_date', '>=', now())
            ->count();

        return [
            'totalFeed' => $totalFeed,
            'lowStockFeeds' => $lowStockFeeds,
            'expiringFeeds' => $expiringFeeds
        ];
    }
}
