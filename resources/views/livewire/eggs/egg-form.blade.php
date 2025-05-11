<form wire:submit="save" class="space-y-6">
    <div>
        <flux:heading size="lg">{{ __('Add New Egg Collection') }}</flux:heading>

        <flux:subheading>
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </flux:subheading>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="mb-4">
            <flux:select name="flock" :label="__('Flock')" wire:model="flock" id="flock">
                <flux:select.option value="">Select a flock</flux:select.option>
                @foreach ($flocks as $flock)
                <flux:select.option value="{{ $flock->id }}">{{ $flock->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    
    
        <div class="mb-4">
            <flux:input
            wire:model="collection_date"
            :label="__('Collection Date')"
            type="date"
            required
            id="collection_date"
            />
        </div>
    
    </div>
    
    
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="mb-4">
        <flux:input
        wire:model="total"
        :label="__('Number of Eggs Collected')"
        type="number"
        required
        min="0"
        id="total"
        />
    </div>


    <div class="mb-4">
        <flux:input
        wire:model="damaged_eggs"
        :label="__('Damaged Eggs Collected')"
        type="number"
        required
        min="0"
        id="damaged_eggs"
        />
    </div>
</div>
    

    <div class="mb-4">
        <flux:select name="worker" :label="__('Collected By')" wire:model.defer="worker" id="type">
            <flux:select.option value="">Select a Worker</flux:select.option>
            @foreach ($workers as $worker)
            <flux:select.option value="{{ (int)$worker->id }}">{{ $worker->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>
    
    

    <div class="flex justify-end space-x-2 rtl:space-x-reverse">
       
            <flux:button variant="filled" wire:click="cancel">{{ __('Cancel') }}</flux:button>
       

        <flux:button variant="primary" type="submit">{{ __('Submit') }}</flux:button>
    </div>
</form>