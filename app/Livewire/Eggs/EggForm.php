<?php

namespace App\Livewire\Eggs;

use App\Models\EggProduction;
use App\Models\Flock;
use App\Models\User;
use Livewire\Component;
use Livewire\Livewire;
use Masmerise\Toaster\Toaster;

class EggForm extends Component
{

    public $total;
    public $collection_date;
    public $flock;
    public $damaged_eggs;
    // public $collected_eggs;
    public $worker;
    public $eggsId;

    public $isEditing = false;

    protected $rules = [
        'total' => 'required|integer',
        'collection_date' => 'required|date',
        'flock' => 'required|integer',
        'damaged_eggs' => 'required|integer',
        'worker' => 'required|integer'
    ];

    public function mount($eggsId = null)
    {
        if ($eggsId) {
            $this->editEggs($eggsId);
        } else {
            $this->reset();
        }
    }

    public function editEggs($eggsId)
    {

        $this->eggsId = $eggsId;
        $egg = EggProduction::findOrFail($eggsId);

        $this->flock = $egg->flock_id;
        $this->total = $egg->total_eggs;
        $this->collection_date = $egg->collection_date;
        $this->worker = $egg->collected_by;
        $this->damaged_eggs = $egg->damaged_eggs;


        $this->isEditing = true;
    }

    public function cancel()
    {
        $this->reset();
        redirect()->route('egg-production');
    }

    public function save()
    {
        // dd();

        $this->validate();

        if ($this->eggsId && $this->isEditing) {
            // Update existing flock
            $egg = EggProduction::findOrFail($this->eggsId);
            $egg->update([
                'flock_id' => $this->flock,
                'total_eggs' => $this->total,
                'damaged_eggs' => $this->damaged_eggs,
                'collection_date' => $this->collection_date,
                'collected_by' => $this->worker,

            ]);

            $message = 'Flock updated successfully!';
        } else {
            // Create new flock
            EggProduction::create([
                'flock_id' => $this->flock,
                'total_eggs' => $this->total,
                'damaged_eggs' => $this->damaged_eggs,
                'collection_date' => $this->collection_date,
                'collected_by' => $this->worker,

            ]);

            $message = 'Collection added successfully!';
        }

        $this->reset();
        // $this->emit('flockSaved');
        Toaster::success($message);
        // session()->flash('success', $message);
    }

    public function render()
    {
        $flocks = Flock::all();
        $workers = User::where('role', 'worker')->get();
        return view(
            'livewire.eggs.egg-form',
            [
                'flocks' => $flocks,
                'workers' => $workers,
            ]
        );
    }
}
