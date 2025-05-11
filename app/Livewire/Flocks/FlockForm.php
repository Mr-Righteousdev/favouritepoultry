<?php

namespace App\Livewire\Flocks;

use App\Models\Flock;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class FlockForm extends Component
{
    public $flockId;
    public $name;
    public $breed;
    public $type = 'broiler';
    public $initial_quantity;
    public $current_quantity;
    public $acquisition_date;
    public $notes;
    public $is_active = true;

    public $isEditing = false;

    protected $listeners = ['editFlock'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'breed' => 'required|string|max:255',
        'type' => 'required|in:broiler,layer,breeder,other',
        'initial_quantity' => 'required|integer|min:1',
        'current_quantity' => 'required|integer|min:0',
        'acquisition_date' => 'required|date|before_or_equal:today',
        'notes' => 'nullable|string',
        'is_active' => 'boolean'
    ];

    public function mount($flockId = null)
    {
        if ($flockId) {
            $this->editFlock($flockId);
        } else {
            $this->resetForm();
            $this->acquisition_date = date('Y-m-d');
        }
    }
    #[\Livewire\Attributes\On('editFlock')]
    public function editFlock($flockId)
    {
        $this->flockId = $flockId;
        $flock = Flock::findOrFail($flockId);

        $this->name = $flock->name;
        $this->breed = $flock->breed;
        $this->type = $flock->type;
        $this->initial_quantity = $flock->initial_quantity;
        $this->current_quantity = $flock->current_quantity;
        $this->acquisition_date = $flock->acquisition_date->format('Y-m-d');
        $this->notes = $flock->notes;
        $this->is_active = $flock->is_active;

        $this->isEditing = true;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // When initial quantity is set and we're not editing, set current quantity to match
        if ($propertyName === 'initial_quantity' && !$this->isEditing) {
            $this->current_quantity = $this->initial_quantity;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->flockId) {
            // Update existing flock
            $flock = Flock::findOrFail($this->flockId);
            $flock->update([
                'name' => $this->name,
                'breed' => $this->breed,
                'type' => $this->type,
                'initial_quantity' => $this->initial_quantity,
                'current_quantity' => $this->current_quantity,
                'acquisition_date' => $this->acquisition_date,
                'notes' => $this->notes,
                'is_active' => $this->is_active,
            ]);

            $message = 'Flock updated successfully!';
            Toaster::success($message);
        } else {
            // Create new flock
            Flock::create([
                'name' => $this->name,
                'breed' => $this->breed,
                'type' => $this->type,
                'initial_quantity' => $this->initial_quantity,
                'current_quantity' => $this->current_quantity,
                'acquisition_date' => $this->acquisition_date,
                'notes' => $this->notes,
                'is_active' => $this->is_active,
            ]);

            $message = 'Flock created successfully!';
            Toaster::success($message);
        }


        $this->resetForm();
        // $this->emit('flockSaved');

        // session()->flash('success', $message);
    }

    public function cancel()
    {
        $this->resetForm();
    }

    public function delete()
    {
        $flock = Flock::findOrFail($this->flockId);
        $flock->delete();
        $this->reset();
        redirect("/flocks");
        Toaster::warning("Flock was deleted successfully!");
    }

    private function resetForm()
    {
        $this->flockId = null;
        $this->name = '';
        $this->breed = '';
        $this->type = 'broiler';
        $this->initial_quantity = '';
        $this->current_quantity = '';
        $this->acquisition_date = date('Y-m-d');
        $this->notes = '';
        $this->is_active = true;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.flocks.flock-form');
    }
}
