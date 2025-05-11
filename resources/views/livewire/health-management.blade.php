
<div>
    <div class="">
        <h1 class="text-2xl font-bold mt-4">Health Management</h1>
        <ol class="flex text-sm my-4">
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li class="mx-2">/</li>
            <li class="text-gray-500">Health Management</li>
        </ol>
        
        
        
        <div class="rounded-lg shadow-md mb-4">
            <div class="flex justify-between items-center px-6 py-3 border-b">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Health Records
                </div>
                <div class="flex gap-2">
                    {{-- <input wire:model="searchTerm" type="text" class="rounded border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2" placeholder="Search Health s..."> --}}
                    <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">Add New Health Record</button>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto">
            <!-- Health Records Table -->
            <livewire:health-table />
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="fixed inset-0 z-51 overflow-y-auto" x-show="$wire.isOpen" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:w-full sm:p-6" 
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                
                <form wire:submit.prevent="store">
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="mb-6 text-lg font-medium text-gray-900">
                            {{ $healthId ? 'Edit Health Record' : 'Add Health Record' }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>
                                <flux:select 
                                wire:model="flock_id"
                                id="flock_id"
                                :label="__('Flock')" >
                                <flux:select.option value="">Select Flock</flux:select.option>
                                @foreach ($flocks as $flock)
                                    <flux:select.option value="{{ $flock->id }}">{{ $flock->name }}</flux:select.option>
                                @endforeach
                            </flux:select>

                                {{-- <label for="flock_id" class="block text-sm font-medium text-gray-700">Flock</label>
                                <select id="flock_id" wire:model="flock_id" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Select Flock</option>
                                    @foreach ($flocks as $flock)
                                        <option value="{{ $flock->id }}">{{ $flock->name }}</option>
                                    @endforeach
                                </select>
                                @error('flock_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:input
                                :label="__('Date')"
                                id="date"
                                wire:model="date"
                                type="date" />
                                {{-- <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" id="date" wire:model="date" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>

                                <flux:select 
                                wire:model="treatment_type"
                                id="treatment_type"
                                :label="__('Treatment Type')" >
                                <flux:select.option value="">Select Type of Treatment</flux:select.option>
                                @foreach ($treatmentTypes as $type)
                                    <flux:select.option value="{{ $type }}">{{ $type }}</flux:select.option>
                                @endforeach
                            </flux:select>

                                {{-- <label for="treatment_type" class="block text-sm font-medium text-gray-700">Treatment Type</label>
                                <select id="treatment_type" wire:model="treatment_type" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @foreach ($treatmentTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('treatment_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:input
                                :label="__('Medication')"
                                id="medication"
                                wire:model="medication"
                                type="text" />

                                {{-- <label for="medication" class="block text-sm font-medium text-gray-700">Medication</label>
                                <input type="text" id="medication" wire:model="medication" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('medication') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>

                                <flux:input
                                :label="__('Dosage')"
                                id="dosage"
                                wire:model="dosage"
                                type="text" />
                                {{-- <label for="dosage" class="block text-sm font-medium text-gray-700">Dosage</label>
                                <input type="text" id="dosage" wire:model="dosage" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('dosage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>

                                <flux:input
                                :label="__('Diagnosis')"
                                id="diagnosis"
                                wire:model="diagnosis"
                                type="text" />
                                {{-- <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis</label>
                                <input type="text" id="diagnosis" wire:model="diagnosis" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('diagnosis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:input
                                :label="__('Symptoms')"
                                id="symptoms"
                                wire:model="symptoms"
                                type="text" />
                                {{-- <label for="symptoms" class="block text-sm font-medium text-gray-700">Symptoms</label>
                                <input type="text" id="symptoms" wire:model="symptoms" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('symptoms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:input
                                :label="__('Treatment Cost')"
                                id="treatment_cost"
                                wire:model="treatment_cost"
                                type="number" 
                                step="0.01" min="0" />
                                {{-- <label for="treatment_cost" class="block text-sm font-medium text-gray-700">Treatment Cost</label>
                                <input type="number" id="treatment_cost" wire:model="treatment_cost" step="0.01" min="0" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('treatment_cost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:input
                                :label="__('Mortality')"
                                id="mortality"
                                wire:model="mortality"
                                type="number" 
                                min="0" />
                                {{-- <label for="mortality" class="block text-sm font-medium text-gray-700">Mortality</label>
                                <input type="number" id="mortality" wire:model="mortality" min="0" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('mortality') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:input
                                :label="__('Next Checkup Date')"
                                id="next_checkup_date"
                                wire:model="next_checkup_date"
                                type="date" />
                                {{-- <label for="next_checkup_date" class="block text-sm font-medium text-gray-700">Next Checkup Date</label>
                                <input type="date" id="next_checkup_date" wire:model="next_checkup_date" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('next_checkup_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>

                            <div>
                                <flux:input
                                :label="__('Treated By')"
                                id="treated_by"
                                wire:model="treated_by"
                                type="text" />
                                {{-- <label for="treated_by" class="block text-sm font-medium text-gray-700">Treated By</label>
                                <input type="text" id="treated_by" wire:model="treated_by" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('treated_by') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div>
                                <flux:select 
                                wire:model="status"
                                id="status"
                                :label="__('Status')" >
                            
                                @foreach ($statuses as $status)
                                    <flux:select.option value="{{ $status }}">{{ ucfirst($status) }}</flux:select.option>
                                @endforeach
                            </flux:select>

                                {{-- <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" wire:model="status" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                            
                            <div class="col-span-3">
                                <flux:textarea 
                                id="notes"
                                wire:model="notes"
                                rows="3"
                                :label="__('Notes')"
                                />

                                {{-- <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea id="notes" wire:model="notes" rows="3" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror --}}
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="button" wire:click="closeModal" 
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="inline-flex justify-center px-4 py-2 ml-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ $healthId ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" x-show="$wire.isViewModalOpen" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block w-full max-w-4xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:w-full sm:p-6" 
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Health Record Details
                        </h3>
                        
                        <div class="flex space-x-2">
                            <button type="button" wire:click="edit({{ $healthId }})" 
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            <button type="button" wire:click="confirmDelete({{ $healthId }})" 
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Flock</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $flocks->where('id', $flock_id)->first()->name ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Date</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $date }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Treatment Type</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $treatment_type }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Medication</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $medication ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Dosage</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $dosage ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Diagnosis</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $diagnosis ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Symptoms</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $symptoms ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Treatment Cost</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $treatment_cost ?? '0.00' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Mortality</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $mortality ?? '0' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Next Checkup Date</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $next_checkup_date ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Treated By</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $treated_by ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status</p>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $status === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                       ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-500">Notes</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $notes ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button type="button" wire:click="closeModal" 
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto" x-show="$wire.isDeleteModalOpen" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:w-full sm:p-6" 
                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-headline">
                                Delete Health Record
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this health record? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="delete" 
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" wire:click="closeModal" 
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
