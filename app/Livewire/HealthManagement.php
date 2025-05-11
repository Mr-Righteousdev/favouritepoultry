<?php

namespace App\Livewire;

use App\Models\Flock;
use App\Models\Health;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

class HealthManagement extends Component
{
    use WithPagination;

    public $isOpen = false;
    public $isDeleteModalOpen = false;
    public $isViewModalOpen = false;
    public $healthId;
    public $flocks = [];
    public $filteredFlockId = '';
    public $searchTerm = '';
    public $statusFilter = '';
    public $dateRange = '';

    // Form fields
    public $flock_id;
    public $date;
    public $treatment_type;
    public $medication;
    public $dosage;
    public $diagnosis;
    public $symptoms;
    public $treatment_cost;
    public $mortality = 0;
    public $notes;
    public $next_checkup_date;
    public $treated_by;
    public $status = 'active';

    public $treatmentTypes = [
        'Vaccination',
        'Deworming',
        'Antibiotics',
        'Vitamins',
        'Disinfection',
        'Other'
    ];

    public $statuses = [
        'active',
        'completed',
        'pending',
        'failed'
    ];

    protected $rules = [
        'flock_id' => 'required|exists:flocks,id',
        'date' => 'required|date',
        'treatment_type' => 'required|string',
        'medication' => 'nullable|string',
        'dosage' => 'nullable|string',
        'diagnosis' => 'nullable|string',
        'symptoms' => 'nullable|string',
        'treatment_cost' => 'nullable|numeric|min:0',
        'mortality' => 'nullable|integer|min:0',
        'notes' => 'nullable|string',
        'next_checkup_date' => 'nullable|date|after_or_equal:date',
        'treated_by' => 'nullable|string',
        'status' => 'required|in:active,completed,pending,failed',
    ];

    protected $listeners = ['refreshHealthManagement' => '$refresh'];

    public function mount()
    {
        $this->loadFlocks();
        $this->date = date('Y-m-d');
        $this->treated_by = Auth::user()->name ?? '';
    }

    public function loadFlocks()
    {
        $this->flocks = Flock::where('is_active', 1)->get();
    }

    public function render()
    {
        $query = Health::with('flock')
            ->when($this->filteredFlockId, function ($q) {
                return $q->where('flock_id', $this->filteredFlockId);
            })
            ->when($this->searchTerm, function ($q) {
                return $q->where(function ($query) {
                    $query->where('treatment_type', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('medication', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('diagnosis', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('symptoms', 'like', '%' . $this->searchTerm . '%')
                        ->orWhereHas('flock', function ($query) {
                            $query->where('name', 'like', '%' . $this->searchTerm . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->dateRange, function ($q) {
                $dates = explode(' to ', $this->dateRange);
                if (count($dates) == 2) {
                    return $q->whereBetween('date', $dates);
                }
                return $q;
            })
            ->orderBy('date', 'desc');

        $healthRecords = $query->paginate(10);

        return view('livewire.health-management', [
            'healthRecords' => $healthRecords
        ]);
    }

    public function openModal()
    {
        $this->reset([
            'flock_id',
            'medication',
            'dosage',
            'diagnosis',
            'symptoms',
            'treatment_cost',
            'mortality',
            'notes',
            'next_checkup_date',
            'healthId'
        ]);
        $this->date = date('Y-m-d');
        $this->status = 'active';
        $this->treatment_type = $this->treatmentTypes[0];
        $this->treated_by = Auth::user()->name ?? '';
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->isDeleteModalOpen = false;
        $this->isViewModalOpen = false;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'flock_id' => $this->flock_id,
            'date' => $this->date,
            'treatment_type' => $this->treatment_type,
            'medication' => $this->medication,
            'dosage' => $this->dosage,
            'diagnosis' => $this->diagnosis,
            'symptoms' => $this->symptoms,
            'treatment_cost' => $this->treatment_cost ?? 0,
            'mortality' => $this->mortality ?? 0,
            'notes' => $this->notes,
            'next_checkup_date' => $this->next_checkup_date,
            'treated_by' => $this->treated_by,
            'status' => $this->status,
        ];

        if ($this->healthId) {
            Health::find($this->healthId)->update($data);
            // session()->flash('message', 'Health record updated successfully.');
            Toaster::success("Health record updated successfully.");
        } else {
            Health::create($data);
            // session()->flash('message', 'Health record created successfully.');
            Toaster::success("Health record created successfully.");
        }

        $this->closeModal();
        $this->reset([
            'flock_id',
            'medication',
            'dosage',
            'diagnosis',
            'symptoms',
            'treatment_cost',
            'mortality',
            'notes',
            'next_checkup_date',
            'healthId'
        ]);
        $this->dispatch('$refresh')->to(HealthTable::class);
    }
    #[\Livewire\Attributes\On('edit')]
    public function edit($id)
    {
        $health = Health::findOrFail($id);
        $this->healthId = $id;
        $this->flock_id = $health->flock_id;
        $this->date = $health->date->format('Y-m-d');
        $this->treatment_type = $health->treatment_type;
        $this->medication = $health->medication;
        $this->dosage = $health->dosage;
        $this->diagnosis = $health->diagnosis;
        $this->symptoms = $health->symptoms;
        $this->treatment_cost = $health->treatment_cost;
        $this->mortality = $health->mortality;
        $this->notes = $health->notes;
        $this->next_checkup_date = $health->next_checkup_date ? $health->next_checkup_date->format('Y-m-d') : null;
        $this->treated_by = $health->treated_by;
        $this->status = $health->status;

        $this->isOpen = true;
    }

    public function confirmDelete($id)
    {
        $this->healthId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        Health::find($this->healthId)->delete();
        session()->flash('message', 'Health record deleted successfully.');
        $this->closeModal();
    }
    #[\Livewire\Attributes\On('view')]
    public function view($id)
    {
        $health = Health::with('flock')->findOrFail($id);
        $this->healthId = $id;
        $this->flock_id = $health->flock_id;
        $this->date = $health->date->format('Y-m-d');
        $this->treatment_type = $health->treatment_type;
        $this->medication = $health->medication;
        $this->dosage = $health->dosage;
        $this->diagnosis = $health->diagnosis;
        $this->symptoms = $health->symptoms;
        $this->treatment_cost = $health->treatment_cost;
        $this->mortality = $health->mortality;
        $this->notes = $health->notes;
        $this->next_checkup_date = $health->next_checkup_date ? $health->next_checkup_date->format('Y-m-d') : null;
        $this->treated_by = $health->treated_by;
        $this->status = $health->status;

        $this->isViewModalOpen = true;
    }

    public function resetFilters()
    {
        $this->reset(['searchTerm', 'filteredFlockId', 'statusFilter', 'dateRange']);
        $this->resetPage();
    }

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingFilteredFlockId()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateRange()
    {
        $this->resetPage();
    }
}
