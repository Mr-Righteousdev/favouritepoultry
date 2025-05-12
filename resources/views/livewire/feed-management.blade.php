<div>
    <div class="">
        <h1 class="text-2xl font-bold mt-4">Feed Management</h1>
        <ol class="flex text-sm my-4">
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li class="mx-2">/</li>
            <li class="text-gray-500">Feed Management</li>
        </ol>
        
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="rounded-lg shadow-md mb-4">
            <div class="flex justify-between items-center px-6 py-3 border-b">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Feed Inventory
                </div>
                <div class="flex gap-2">
                    {{-- <input wire:model="searchTerm" type="text" class="rounded border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 px-3 py-2" placeholder="Search feeds..."> --}}
                    <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">Add New Feed</button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-600 text-white rounded-lg shadow-md">
                        <div class="px-4 py-5">
                            <h4 class="text-lg font-bold">Total Feed: {{ $this->getFeedSummary()['totalFeed'] }} kg</h4>
                        </div>
                    </div>
                    <div class="bg-yellow-500 text-white rounded-lg shadow-md">
                        <div class="px-4 py-5">
                            <h4 class="text-lg font-bold">Low Stock: {{ $this->getFeedSummary()['lowStockFeeds'] }} items</h4>
                        </div>
                    </div>
                    <div class="bg-red-600 text-white rounded-lg shadow-md">
                        <div class="px-4 py-5">
                            <h4 class="text-lg font-bold">Expiring Soon: {{ $this->getFeedSummary()['expiringFeeds'] }} items</h4>
                        </div>
                    </div>
                </div>
                
                <div class="">
                    <livewire:feed-table />
                </div>
                
               
            </div>
        </div>
    </div>
    
    <!-- Feed Form Modal -->
    @if($isOpen)
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $isEditMode ? 'Edit Feed' : 'Add New Feed' }}
                            </h3>
                            <div class="mt-4">
                                <form wire:submit.prevent="store">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <flux:input 
                                            wire:model="feed_name"
                                            id="feed_name"
                                            :label="__('Feed Name')"
                                            type="text"
                                            required />
                                            
                                        </div>
                                        <div>
                                            <flux:select 
                                            wire:model="feed_type"
                                            id="feed_type"
                                            :label="__('Feed Type')"
                                            required >
                                                <flux:select.option value="">Select Feed Type</flux:select.option>
                                                <flux:select.option value="Starter">Starter</flux:select.option>
                                                <flux:select.option value="Grower">Grower</flux:select.option>
                                                <flux:select.option value="Layer">Layer</flux:select.option>
                                                <flux:select.option value="Finisher">Finisher</flux:select.option>
                                                <flux:select.option value="Suppliment">Suppliment</flux:select.option>                             
                                            </flux:select>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <flux:input 
                                            wire:model="quantity"
                                            id="quantity"
                                            :label="__('Quantity (kg)')"
                                            type="number"
                                            step="0.01" min="0" required />
                                            
                                        </div>
                                        <div>
                                            

    
                                            <flux:field>    
                                                <flux:label>Unit Price</flux:label>    
                                                <flux:input.group>        
                                                    <flux:input.group.prefix>Ugx</flux:input.group.prefix>        
                                                    <flux:input wire:model="unit_price" placeholder="200" type="number" required />    
                                                </flux:input.group>    
                                                <flux:error name="unit_price" />
                                            </flux:field>



                                            
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <flux:input 
                                            wire:model="purchase_date"
                                            id="purchase_date"
                                            :label="__('Purchase Date')"
                                            type="date"
                                            required />
                                            
                                        </div>
                                        <div>
                                            <flux:input 
                                            wire:model="expiry_date"
                                            id="expiry_date"
                                            :label="__('Expiry Date')"
                                            type="date" />
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <flux:input 
                                        wire:model="supplier"
                                        id="supplier"
                                        :label="__('Supplier')"
                                        type="text" />
                                        
                                    </div>
                                        
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="store" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ $isEditMode ? 'Update' : 'Save' }}
                    </button>
                    <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Feed Usage Modal -->
    @if($isUsageOpen)
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Record Feed Usage
                            </h3>
                            <div class="mt-4">
                                <form wire:submit.prevent="storeUsage">
                                    <div class="mb-4">

                                        <flux:select id="flock_id" wire:model="flock_id" >
                                            <flux:select.option value="">Select Flock</flux:select.option>
                                            @foreach($flocks as $flock)
                                                <flux:select.option value="{{ $flock->id }}">{{ $flock->name }} ({{ $flock->breed }})</flux:select.option>
                                            @endforeach
                                        </flux:select>

                                        
                                    </div>
                                    
                                    <div class="mb-4">
                                        <flux:input 
                                        :label="__('Amount Used (kg)')"
                                        wire:model="amount_used"
                                        type="number"
                                        id="amount_used"
                                        step="0.01" min="0.01" required
                                        />
                                    </div>
                                    
                                    <div class="mb-4">
                                        <flux:input
                                        type="date"
                                        id="usage_date"
                                        wire:model="usage_date"
                                        :label="__('Usage Date')"
                                        required
                                        />
                                        
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="storeUsage" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Record Usage
                    </button>
                    <button type="button" wire:click="closeUsageModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>


