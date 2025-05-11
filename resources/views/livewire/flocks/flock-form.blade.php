
<form wire:submit.prevent="save">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="mb-4">
                
                <flux:input
            wire:model.defer="name"
            :label="__('Flock Name')"
            type="text"
            required
               
            placeholder="Enter flock name."
        />
            </div>
        </div>
        <div>
            <div class="mb-4">
            
                <flux:input
            wire:model.defer="breed"
            :label="__('Breed')"
            type="text"
            required
           id="breed"
     
            placeholder="Enter breed name"
        />
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <div class="mb-4">
            
                <flux:select name="type" :label="__('Type')" wire:model.defer="type" id="type">
                    <flux:select.option value="broiler">Broiler</flux:select.option>
                    <flux:select.option value="layer">Layer</flux:select.option>
                    <flux:select.option value="breeder">Breeder</flux:select.option>
                    <flux:select.option value="other">Other</flux:select.option>
                </flux:select>
            </div>
        </div>
        <div>
            <div class="mb-4">
              
                <flux:input
                wire:model.defer="acquisition_date"
                :label="__('Acquisition Date')"
                type="date"
                required
                id="acquisition_date"
                />
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
            <div class="mb-4">
             
                    <flux:input
            wire:model="initial_quantity"
            :label="__('Initial Quantity')"
            type="number"
            min="1"
            required
           id="initial_quantity"
     
           
        />
            </div>
        </div>
        <div>
            <div class="mb-4">
                
              
                    <flux:input
                        wire:model.defer="current_quantity"
                        :label="__('Current Quantity')"
                        type="number"
                        required
                        min="0"
                        id="current_quantity"
                    />
            </div>
        </div>
    </div>
    
    <div class="mb-4">
       

        <flux:textarea 
        :label="__('Notes')"
        name="notes"
        wire:model.defer="notes"
        id="notes"
        rows="3"
        placeholder="Add any additional notes please." />
    </div>
    
    <div class="flex items-center mb-4">
        <input 
            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" 
            type="checkbox" 
            id="is_active" 
            wire:model.defer="is_active">
        <label class="ml-2 block text-sm text-gray-900" for="is_active">
            Active Flock
        </label>
    </div>
    
    <div class="flex justify-between">
        <a 
            href="/flocks"
            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
            wire:click="cancel" >
            Cancel
        </a>
        @if ($isEditing)
        <flux:button variant="danger" wire:click="delete">{{ __('Delete flock') }}</flux:button>
        @endif
        
        <button 
            type="submit" 
            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
            wire:loading.attr="disabled">
            <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ $isEditing ? 'Update' : 'Save' }} Flock
        </button>

    </div>
</form>
